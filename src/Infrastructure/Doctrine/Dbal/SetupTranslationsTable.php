<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class SetupTranslationsTable
{
    public function __construct(
        private readonly string $tableName = 'tailr_translations'
    ) {
    }

    public function __invoke(Connection $dbalConnection): void
    {
        $schemaManager = $dbalConnection->createSchemaManager();
        $platform = $dbalConnection->getDatabasePlatform();

        $schemaDiff = $schemaManager
            ->createComparator()
            ->compareSchemas(
                $schemaManager->introspectSchema(),
                $this->getSchema($dbalConnection)
            );

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
        $table = $schema->createTable($this->tableName);
        $table->addColumn('id', Types::BIGINT)
            ->setAutoincrement(true)
            ->setNotnull(true);
        $table->addColumn('locale', Types::STRING)
            ->setLength(2)
            ->setNotnull(true);
        $table->addColumn('domain', Types::STRING)
            ->setLength(255)
            ->setNotnull(true);
        $table->addColumn('key', Types::TEXT)
            ->setNotnull(true);
        $table->addColumn('translation', Types::TEXT)
            ->setNotnull(true);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE)
            ->setNotnull(true);
        $table->addColumn('updated_at', Types::DATETIME_IMMUTABLE)
            ->setNotnull(false);
        $table->setPrimaryKey(['id']);
    }
}
