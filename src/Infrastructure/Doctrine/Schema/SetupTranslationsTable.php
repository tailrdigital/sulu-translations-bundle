<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Schema;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\DatabaseConnectionManager;

class SetupTranslationsTable
{
    public function __construct(
        private readonly DatabaseConnectionManager $databaseConnectionManager
    ) {
    }

    public function execute(): void
    {
        $dbalConnection = $this->databaseConnectionManager->getConnection();

        $schemaManager = $dbalConnection->createSchemaManager();
        $platform = $dbalConnection->getDatabasePlatform();

        $schemaDiff = $schemaManager
            ->createComparator()
            ->compareSchemas(
                $schemaManager->introspectSchema(),
                $this->getSchema($dbalConnection)
            );

        /** @psalm-suppress DeprecatedMethod */
        foreach ($schemaDiff->toSaveSql($platform) as $sql) {
            $dbalConnection->executeStatement($sql);
        }
    }

    private function getSchema(Connection $dbalConnection): Schema
    {
        $schema = new Schema([], [], $dbalConnection->createSchemaManager()->createSchemaConfig());
        $this->addTableToSchema($schema);

        return $schema;
    }

    private function addTableToSchema(Schema $schema): void
    {
        TranslationTable::createTable($schema);
    }
}
