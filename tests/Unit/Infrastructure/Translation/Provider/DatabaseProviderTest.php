<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Infrastructure\Translation\Provider;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Translation\Provider\ProviderInterface;
use Symfony\Component\Translation\TranslatorBag;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\DatabaseProvider;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Loader;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Remover;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Writer;

class DatabaseProviderTest extends TestCase
{
    use ProphecyTrait;

    private DatabaseProvider $provider;
    private Writer|ObjectProphecy $writer;
    private Loader|ObjectProphecy $loader;
    private Remover|ObjectProphecy $remover;

    protected function setUp(): void
    {
        $this->writer = $this->prophesize(Writer::class);
        $this->loader = $this->prophesize(Loader::class);
        $this->remover = $this->prophesize(Remover::class);

        $this->provider = new DatabaseProvider(
            'default',
            $this->writer->reveal(),
            $this->loader->reveal(),
            $this->remover->reveal(),
        );
    }

    /** @test */
    public function it_is_a_symfony_translation_provider(): void
    {
        self::assertInstanceOf(ProviderInterface::class, $this->provider);
    }

    /** @test */
    public function it_is_stringable(): void
    {
        self::assertSame('database://default', (string) $this->provider);
    }

    /** @test */
    public function it_can_load_or_read_translations(): void
    {
        $this->loader
            ->execute($domains = ['messages'], $locales = ['en'])
            ->willReturn($translatorBag = new TranslatorBag())
            ->shouldBeCalled();

        self::assertSame(
            $translatorBag,
            $this->provider->read($domains, $locales)
        );
    }

    /** @test */
    public function it_can_write_translations(): void
    {
        $this->writer
            ->execute($translatorBag = new TranslatorBag())
            ->shouldBeCalled();

        $this->provider->write($translatorBag);
    }

    /** @test */
    public function it_can_remove_translations(): void
    {
        $this->remover
            ->execute($translatorBag = new TranslatorBag())
            ->shouldBeCalled();

        $this->provider->delete($translatorBag);
    }
}
