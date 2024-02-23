<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Infrastructure\Translation\Provider;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Translation\Exception\LogicException;
use Symfony\Component\Translation\Exception\UnsupportedSchemeException;
use Symfony\Component\Translation\Provider\Dsn;
use Symfony\Component\Translation\Provider\ProviderInterface;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\DatabaseProvider;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\DatabaseProviderFactory;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Loader;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Remover;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Writer;

class DatabaseProviderFactoryTest extends TestCase
{
    use ProphecyTrait;

    private Writer|ObjectProphecy $writer;
    private Loader|ObjectProphecy $loader;
    private Remover|ObjectProphecy $remover;
    private DatabaseProviderFactory $factory;

    protected function setUp(): void
    {
        $this->writer = $this->prophesize(Writer::class);
        $this->loader = $this->prophesize(Loader::class);
        $this->remover = $this->prophesize(Remover::class);

        $this->factory = new DatabaseProviderFactory(
            $this->writer->reveal(),
            $this->loader->reveal(),
            $this->remover->reveal(),
        );
    }

    /** @test */
    public function it_cat_create_a_database_provider(): void
    {
        $provider = $this->factory->create(new Dsn('database://tailr_translations'));
        self::assertInstanceOf(ProviderInterface::class, $provider);
        self::assertInstanceOf(DatabaseProvider::class, $provider);
    }

    /** @test */
    public function it_will_throw_an_exception_with_invalid_scheme_given(): void
    {
        self::expectException(UnsupportedSchemeException::class);
        $this->factory->create(new Dsn('invalid://tailr_translations'));
    }

    /** @test */
    public function it_will_throw_an_exception_with_invalid_name_given(): void
    {
        self::expectException(LogicException::class);
        $this->factory->create(new Dsn('database://invalid-name'));
    }
}