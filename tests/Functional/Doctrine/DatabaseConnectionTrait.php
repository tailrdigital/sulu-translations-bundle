<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Functional\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\DatabaseConnectionManager;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Schema\TranslationTable;

use function Psl\Result\wrap;

trait DatabaseConnectionTrait
{
    use ProphecyTrait;

    protected Connection $connection;

    protected function setupConnection(): void
    {
        $this->connection = DriverManager::getConnection(['url' => $_ENV['DATABASE_URL']]);
    }

    /**
     * @return DatabaseConnectionManager|ObjectProphecy
     */
    protected function createDatabaseConnectionMock(Connection $connection): mixed
    {
        $databaseConnectionManager = $this->prophesize(DatabaseConnectionManager::class);
        $databaseConnectionManager->getConnection()->willReturn($connection);

        return $databaseConnectionManager;
    }

    protected function cleanup(): void
    {
        wrap(fn () => $this->connection->executeQuery('DROP TABLE '.TranslationTable::NAME));
    }
}
