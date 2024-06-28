<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Query;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Tailr\SuluTranslationsBundle\Domain\Query\FetchTranslation;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class FetchTranslationTest extends TestCase
{
    use ProphecyTrait;

    private TranslationRepository|ObjectProphecy $repository;
    private FetchTranslation $fetcher;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(TranslationRepository::class);

        $this->fetcher = new FetchTranslation(
            $this->repository->reveal(),
        );
    }

    /** @test */
    public function it_can_fetch_a_translation(): void
    {
        $this->repository->findById($id = 1)
            ->willReturn($translation = Translations::create());

        self::assertSame($translation, ($this->fetcher)($id));
    }
}
