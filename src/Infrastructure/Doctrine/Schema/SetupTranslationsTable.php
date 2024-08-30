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
        $schemaDiff = $schemaManager->createComparator()
            ->compareSchemas($schemaManager->introspectSchema(), $this->getSchema($dbalConnection));
        $platform = $dbalConnection->getDatabasePlatform();

        if ($platform->supportsSchemas()) {
            foreach ($schemaDiff->getCreatedSchemas() as $schema) {
                $dbalConnection->executeStatement($platform->getCreateSchemaSQL($schema));
            }
        }

        if ($platform->supportsSequences()) {
            foreach ($schemaDiff->getAlteredSequences() as $sequence) {
                $dbalConnection->executeStatement($platform->getAlterSequenceSQL($sequence));
            }

            foreach ($schemaDiff->getCreatedSequences() as $sequence) {
                $dbalConnection->executeStatement($platform->getCreateSequenceSQL($sequence));
            }
        }

        foreach ($platform->getCreateTablesSQL($schemaDiff->getCreatedTables()) as $sql) {
            $dbalConnection->executeStatement($sql);
        }

        foreach ($schemaDiff->getAlteredTables() as $tableDiff) {
            foreach ($platform->getAlterTableSQL($tableDiff) as $sql) {
                $dbalConnection->executeStatement($sql);
            }
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
