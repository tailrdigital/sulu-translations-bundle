<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Infrastructure\Translation\Provider;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Remover;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\TranslationBags;

class RemoverTest extends TestCase
{
    use ProphecyTrait;

    private TranslationRepository|ObjectProphecy $repository;
    private Remover $remover;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(TranslationRepository::class);

        $this->remover = new Remover(
            $this->repository->reveal(),
        );
    }

    /** @test */
    public function it_can_remove_translations_via_translation_repository(): void
    {
        $translationBag = TranslationBags::simple();
        $this->repository
            ->removeByKeyLocaleDomain($key = 'app.foo', $locale = 'en', $domain = 'messages')
            ->shouldBeCalledOnce();

        $this->remover->execute($translationBag);
    }
}
