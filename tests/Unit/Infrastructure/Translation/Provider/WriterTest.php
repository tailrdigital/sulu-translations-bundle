<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Infrastructure\Translation\Provider;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Clock\MockClock;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Writer;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\TranslationBags;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class WriterTest extends TestCase
{
    use ProphecyTrait;

    private \DateTimeImmutable $now;
    private ClockInterface|ObjectProphecy $clock;
    private TranslationRepository|ObjectProphecy $repository;
    private Writer $writer;

    protected function setUp(): void
    {
        $this->clock = new MockClock($this->now = new \DateTimeImmutable());
        $this->repository = $this->prophesize(TranslationRepository::class);

        $this->writer = new Writer(
            $this->clock,
            $this->repository->reveal(),
        );
    }

    /** @test */
    public function it_can_write_new_translations_via_translation_repository(): void
    {
        $translationBag = TranslationBags::simple();
        $this->repository
            ->findByKeyLocaleDomain($key = 'app.foo', $locale = 'en', $domain = 'messages')
            ->willReturn(null)
            ->shouldBeCalledOnce();
        $this->repository->save(
            Argument::that(
                fn (Translation $translation) => $this->now->format('Y-m-d') === $translation->getCreatedAt()->format('Y-m-d')
                    && $key === $translation->getKey()
                    && $locale === $translation->getLocale()
                    && $domain === $translation->getDomain()
                    && 'Foo' === $translation->getTranslation()
            )
        )->shouldBeCalledOnce();

        $this->writer->execute($translationBag);
    }

    /** @test */
    public function it_will_update_existing_translations(): void
    {
        $translationBag = TranslationBags::simple();
        $this->repository
            ->findByKeyLocaleDomain('app.foo', 'en', 'messages')
            ->willReturn($existingTranslations = Translations::create());
        $this->repository->save(
            Argument::that(
                fn (Translation $translation) => $existingTranslations === $translation
                    && $this->now->format('Y-m-d') === $translation->getUpdatedAt()->format('Y-m-d')
                    && 'Foo' === $translation->getTranslation()
            )
        )->shouldBeCalledOnce();

        $this->writer->execute($translationBag);
    }
}
