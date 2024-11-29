<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Query;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Tailr\SuluTranslationsBundle\Domain\Model\TranslationCollection;
use Tailr\SuluTranslationsBundle\Domain\Query\FetchTranslations;
use Tailr\SuluTranslationsBundle\Domain\Query\SearchCriteria;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class FetchTranslationsTest extends TestCase
{
    use ProphecyTrait;

    private TranslationRepository|ObjectProphecy $repository;
    private FetchTranslations $fetcher;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(TranslationRepository::class);

        $this->fetcher = new FetchTranslations(
            $this->repository->reveal(),
        );
    }

    /** @test */
    public function it_can_fetch_translations(): void
    {
        $this->repository->findByCriteria($criteria = new SearchCriteria(
            'searchValue',
            ['locale' => 'en'],
            'columnValue',
            'ASC',
            0,
            20
        ))->willReturn($collection = new TranslationCollection(Translations::create()));
        $this->repository->countByCriteria($criteria)->willReturn($count = 1);

        $result = ($this->fetcher)($criteria);

        self::assertSame($collection, $result->translationCollection());
        self::assertSame($count, $result->totalCount());
    }
}
