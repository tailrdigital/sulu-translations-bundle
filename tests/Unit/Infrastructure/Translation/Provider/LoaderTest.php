<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Infrastructure\Translation\Provider;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Loader;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class LoaderTest extends TestCase
{
    use ProphecyTrait;

    private TranslationRepository|ObjectProphecy $repository;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(TranslationRepository::class);

        $this->loader = new Loader(
            $this->repository->reveal()
        );
    }

    /** @test */
    public function it_can_load_translations_from_translation_repository(): void
    {
        $this->repository
            ->findAllByLocaleDomain($localeEn = 'en', $domain = 'messages')
            ->willReturn(
                [
                    Translations::create('app.foo', $expectedValueEn1 = 'Foo'),
                    Translations::create('app.bar', $expectedValueEn2 = 'Bar'),
                ]
            );
        $this->repository
            ->findAllByLocaleDomain($localeNl = 'nl', $domain)
            ->willReturn(
                [
                    Translations::create('app.foo', $expectedValueNl1 = 'Foo NL'),
                    Translations::create('app.bar', $expectedValueNl2 = 'Bar NL'),
                ]
            );

        $bag = $this->loader->execute([$domain], [$localeEn, $localeNl]);
        $catalogEn = $bag->getCatalogue($localeEn);
        $catalogNl = $bag->getCatalogue($localeNl);
        self::assertSame($expectedValueEn1, $catalogEn->get('app.foo'));
        self::assertSame($expectedValueEn2, $catalogEn->get('app.bar'));
        self::assertSame($expectedValueNl1, $catalogNl->get('app.foo'));
        self::assertSame($expectedValueNl2, $catalogNl->get('app.bar'));
    }
}
