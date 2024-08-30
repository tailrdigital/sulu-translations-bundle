<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Schema;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

use function Psl\Vec\map;

class TranslationTable
{
    public const NAME = 'tailr_translations';

    public static function name(): string
    {
        return self::NAME;
    }

    /**
     * @return array<array-key, string>
     */
    public static function selectColumns(): array
    {
        $name = static::name();

        return map(static::createTable()->getColumns(), function (Column $column) use ($name): string {
            return sprintf('%s.%s AS %1$s_%2$s',
                $name,
                $column->getName()
            );
        });
    }

    /**
     * @return array<string, Type>
     */
    public static function columnTypes(): array
    {
        return array_merge(...map(static::createTable()->getColumns(), function (Column $column): array {
            return [$column->getName() => $column->getType()];
        }));
    }

    public static function createTable(?Schema $schema = null): Table
    {
        $table = $schema
            ? $schema->createTable(self::NAME)
            : new Table(self::NAME);
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

        return $table;
    }
}
