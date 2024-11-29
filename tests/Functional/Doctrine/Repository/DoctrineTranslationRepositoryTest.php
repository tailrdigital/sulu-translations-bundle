<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Functional\Doctrine\Repository;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Exception\TranslationNotFoundException;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Query\SearchCriteria;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Mapper\TranslationMapper;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Repository\DoctrineTranslationRepository;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Schema\SetupTranslationsTable;
use Tailr\SuluTranslationsBundle\Tests\Functional\Doctrine\DatabaseConnectionTrait;

class DoctrineTranslationRepositoryTest extends TestCase
{
    use DatabaseConnectionTrait;
    private DoctrineTranslationRepository $repository;
    private SetupTranslationsTable $setup;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupConnection();
        $databaseConnectionManager = $this->createDatabaseConnectionMock($this->connection);
        $this->repository = new DoctrineTranslationRepository(
            $databaseConnectionManager->reveal(),
            new TranslationMapper(),
        );
        $this->setup = new SetupTranslationsTable(
            $databaseConnectionManager->reveal()
        );
        $this->cleanup();
        $this->createFixtures();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->cleanup();
    }

    /** @test */
    public function it_can_find_translation_by_id(): void
    {
        $translation = $this->repository->findById(1);

        self::assertSame(1, $translation->getId());
        self::assertSame('App Title', $translation->getTranslation());
    }

    /** @test */
    public function it_can_find_all_translations_by_locale_and_domain(): void
    {
        $translationsDomainMessages = $this->repository->findAllByLocaleDomain('en', 'messages');
        $translationsDomainValidation = $this->repository->findAllByLocaleDomain('nl', 'validation');

        self::assertCount(2, $translationsDomainMessages);
        self::assertCount(1, $translationsDomainValidation);
    }

    /** @test */
    public function it_can_find_a_translation_by_key_locale_and_domain(): void
    {
        $translation = $this->repository->findByKeyLocaleDomain('app.title', 'nl', 'messages');
        self::assertSame('App Titel', $translation->getTranslation());

        $notFoundTranslation = $this->repository->findByKeyLocaleDomain('unknown.key', 'nl', 'messages');
        self::assertNull($notFoundTranslation);
    }

    /** @test  */
    public function it_can_find_translations_by_criteria(): void
    {
        $criteria = new SearchCriteria('pp.tit', ['locale' => null, 'translationKey' => 'app.title'], 'locale', 'ASC', 0, 10);
        $collection = iterator_to_array($this->repository->findByCriteria($criteria));
        $count = $this->repository->countByCriteria($criteria);
        self::assertSame('App Title', $collection[0]->getTranslation());
        self::assertSame('App Titel', $collection[1]->getTranslation());
        self::assertCount(2, $collection);
        self::assertSame(2, $count);

        $criteria = new SearchCriteria('', ['locale' => 'en', 'domain' => 'messages'], 'locale', 'ASC', 0, 10);
        $collection = iterator_to_array($this->repository->findByCriteria($criteria));
        $count = $this->repository->countByCriteria($criteria);
        self::assertSame('App Title', $collection[0]->getTranslation());
        self::assertSame('App Description', $collection[1]->getTranslation());
        self::assertCount(2, $collection);
        self::assertSame(2, $count);

        $criteria = new SearchCriteria('', ['locale' => 'en', 'domain' => 'messages', 'translationKey' => 'app.title'], 'locale', 'ASC', 0, 10);
        $collection = iterator_to_array($this->repository->findByCriteria($criteria));
        $count = $this->repository->countByCriteria($criteria);
        self::assertSame('App Title', $collection[0]->getTranslation());
        self::assertCount(1, $collection);
        self::assertSame(1, $count);

        $criteria = new SearchCriteria('pp.tit', [], 'locale', 'DESC', 0, 10);
        $collection = iterator_to_array($this->repository->findByCriteria($criteria));
        self::assertSame('App Titel', $collection[0]->getTranslation());
        self::assertSame('App Title', $collection[1]->getTranslation());

        $criteria = new SearchCriteria('pp.tit', [], 'locale', 'ASC', 0, 1);
        $collection = iterator_to_array($this->repository->findByCriteria($criteria));
        self::assertSame('App Title', $collection[0]->getTranslation());

        $criteria = new SearchCriteria('pp.tit', [], 'locale', 'ASC', 1, 1);
        $collection = iterator_to_array($this->repository->findByCriteria($criteria));
        self::assertSame('App Titel', $collection[0]->getTranslation());

        $criteria = new SearchCriteria('app.key.not.exists', [], 'locale', 'ASC', 0, 10);
        $collection = $this->repository->findByCriteria($criteria);
        $count = $this->repository->countByCriteria($criteria);
        self::assertCount(0, $collection);
        self::assertSame(0, $count);
    }

    /** @test */
    public function it_can_delete_a_translation_by_id(): void
    {
        $this->repository->deleteById(1);
        self::expectException(TranslationNotFoundException::class);
        $this->repository->findById(1);
    }

    /** @test */
    public function it_can_delete_a_translation_by_key_locale_and_domain(): void
    {
        $this->repository->deleteByKeyLocaleDomain('app.title', 'nl', 'messages');
        $notFoundTranslation = $this->repository->findByKeyLocaleDomain('app.title', 'nl', 'messages');
        self::assertNull($notFoundTranslation);
    }

    /** @test */
    public function it_can_create_a_translation(): void
    {
        $this->repository->create(Translation::create(
            'en',
            'messages',
            'app.new.key',
            'App New Key',
            new \DateTimeImmutable(),
        ));

        $translation = $this->repository->findByKeyLocaleDomain('app.new.key', 'en', 'messages');
        self::assertSame('App New Key', $translation->getTranslation());
    }

    /** @test */
    public function it_can_update_a_translation(): void
    {
        $this->repository->create(Translation::create(
            'en',
            'messages',
            'app.new.update',
            'App New Update',
            new \DateTimeImmutable(),
        ));

        $translation = $this->repository->findByKeyLocaleDomain('app.new.update', 'en', 'messages');
        $this->repository->update($translation->patch('App New Updated Value', new \DateTimeImmutable()));

        $updatedTranslation = $this->repository->findByKeyLocaleDomain('app.new.update', 'en', 'messages');
        self::assertSame('App New Updated Value', $updatedTranslation->getTranslation());
    }

    protected function createFixtures(): void
    {
        $this->setup->execute();

        $this->repository->create(Translation::create(
            'en',
            'messages',
            'app.title',
            'App Title',
            new \DateTimeImmutable(),
        ));
        $this->repository->create(Translation::create(
            'nl',
            'messages',
            'app.title',
            'App Titel',
            new \DateTimeImmutable(),
        ));
        $this->repository->create(Translation::create(
            'en',
            'messages',
            'app.description',
            'App Description',
            new \DateTimeImmutable(),
        ));
        $this->repository->create(Translation::create(
            'nl',
            'messages',
            'app.description',
            'App Omschrijving',
            new \DateTimeImmutable(),
        ));
        $this->repository->create(Translation::create(
            'en',
            'validation',
            'app.required',
            'Required',
            new \DateTimeImmutable(),
        ));
        $this->repository->create(Translation::create(
            'nl',
            'validation',
            'app.required',
            'Verplicht',
            new \DateTimeImmutable(),
        ));
    }
}
