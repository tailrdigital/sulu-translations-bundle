<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Translation\Provider\ProviderInterface;
use Symfony\Component\Translation\Provider\TranslationProviderCollection;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\DatabaseConnectionManager;

class DatabaseConnectionManagerTest extends TestCase
{
    use ProphecyTrait;

    private ProviderInterface|ObjectProphecy $provider;
    private ObjectProphecy|TranslationProviderCollection $providersCollection;
    private ObjectProphecy|ManagerRegistry $doctrineManagerRegistry;
    private DatabaseConnectionManager $databaseConnectionManager;

    protected function setUp(): void
    {
        $this->provider = $this->prophesize(ProviderInterface::class);
        $this->providersCollection = new TranslationProviderCollection(
            ['database' => $this->provider->reveal()]
        );
        $this->doctrineManagerRegistry = $this->prophesize(ManagerRegistry::class);

        $this->databaseConnectionManager = new DatabaseConnectionManager(
            $this->providersCollection,
            $this->doctrineManagerRegistry->reveal(),
        );
    }

    /** @test */
    public function it_can_return_the_configured_dbal_connection_based_on_translation_provider_dsn(): void
    {
        $this->provider->__toString()->willReturn('database://default');
        $connection = $this->prophesize(Connection::class);
        $this->doctrineManagerRegistry->getConnection('default')
            ->shouldBeCalledOnce()
            ->willReturn($connection->reveal());

        $actualConnection = $this->databaseConnectionManager->getConnection();

        self::assertSame($connection->reveal(), $actualConnection);
    }

    /** @test */
    public function it_will_trigger_readable_exception_when_dsn_config_is_invalid(): void
    {
        $this->provider->__toString()->willReturn('database://unknown_dbal_connection');
        $this->doctrineManagerRegistry->getConnection('unknown_dbal_connection')
            ->willThrow(new \InvalidArgumentException('Connection not configured.'));

        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Doctrine connection not found. Please check the DSN configuration of your database translator provider.');

        $this->databaseConnectionManager->getConnection();
    }
}
