<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Tailr\SuluTranslationsBundle\Domain\Exception\TranslationNotFoundException;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Model\TranslationCollection;
use Tailr\SuluTranslationsBundle\Domain\Query\SearchCriteria;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\DatabaseConnectionManager;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Mapper\TranslationMapper;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Schema\TranslationTable;

use function Psl\Str\lowercase;
use function Psl\Type\nullable;
use function Psl\Type\scalar;
use function Psl\Vec\map;
use function Symfony\Component\String\u;

class DoctrineTranslationRepository implements TranslationRepository
{
    public function __construct(
        private readonly DatabaseConnectionManager $manager,
        private readonly TranslationMapper $mapper,
    ) {
    }

    public function findById(int $id): Translation
    {
        $connection = $this->getConnection();

        $qb = $connection->createQueryBuilder();
        $qb->select(TranslationTable::selectColumns())
            ->from(TranslationTable::NAME)
            ->where('id = :id')
            ->setParameter('id', $id);

        $result = $connection->fetchAssociative(
            $qb->getSQL(),
            $qb->getParameters(),
            $qb->getParameterTypes()
        );

        if (false === $result) {
            throw TranslationNotFoundException::withId($id);
        }

        return $this->mapper->fromDb(
            $result
        );
    }

    public function findAllByLocaleDomain(string $locale, string $domain): TranslationCollection
    {
        $connection = $this->getConnection();

        $qb = $connection->createQueryBuilder();
        $qb->select(TranslationTable::selectColumns())
            ->from(TranslationTable::NAME)
            ->andWhere('domain = :domain')
            ->andWhere('locale = :locale')
            ->setParameter('domain', $domain)
            ->setParameter('locale', $locale);

        $result = $connection->fetchAllAssociative(
            $qb->getSQL(),
            $qb->getParameters(),
            $qb->getParameterTypes()
        );

        return new TranslationCollection(
            ...map(
                $result,
                fn (array $row): Translation => $this->mapper->fromDb($row)
            )
        );
    }

    public function findByCriteria(SearchCriteria $criteria): TranslationCollection
    {
        $connection = $this->getConnection();
        $qb = $this->buildQuery($criteria);

        $sortColumn = $criteria->sortColumn();
        $sortDirection = $criteria->sortDirection();
        if (null !== $sortColumn && null !== $sortDirection) {
            $qb->orderBy(
                u($sortColumn)->snake()->toString(),
                u($sortDirection)->snake()->toString()
            );
        }
        $qb->setMaxResults($criteria->limit());
        $qb->setFirstResult($criteria->offset());

        $result = $connection->fetchAllAssociative(
            $qb->getSQL(),
            $qb->getParameters(),
            $qb->getParameterTypes()
        );

        return new TranslationCollection(
            ...map(
                $result,
                fn (array $row): Translation => $this->mapper->fromDb($row)
            )
        );
    }

    public function countByCriteria(SearchCriteria $criteria): int
    {
        $connection = $this->getConnection();
        $qb = $this->buildQuery($criteria);
        $qb->select('count(id)');

        return (int) $connection->fetchOne(
            $qb->getSQL(),
            $qb->getParameters(),
            $qb->getParameterTypes()
        );
    }

    private function buildQuery(SearchCriteria $criteria): QueryBuilder
    {
        $connection = $this->getConnection();

        $qb = $connection->createQueryBuilder();
        $qb->select(TranslationTable::selectColumns())
            ->from(TranslationTable::NAME);

        if ($search = $criteria->searchString()) {
            $qb->andWhere(
                $qb->expr()->or(
                    $qb->expr()->like('lower(translation_key)', ':search'),
                    $qb->expr()->like('lower(translation)', ':search')
                )
            )->setParameter('search', '%'.lowercase($search).'%');
        }

        /**
         * @var string $column
         * @var mixed $value
         */
        foreach ($criteria->filters() as $column => $value) {
            if (null === $value) {
                continue;
            }

            $qb->andWhere(
                $qb->expr()->eq(u($column)->snake()->toString(), ':'.$column)
            )->setParameter($column, $value);
        }

        return $qb;
    }

    public function findByKeyLocaleDomain(string $key, string $locale, string $domain): ?Translation
    {
        $connection = $this->getConnection();

        $qb = $connection->createQueryBuilder();
        $qb->select(TranslationTable::selectColumns())
            ->from(TranslationTable::NAME)
            ->where('translation_key = :translationKey')
            ->andWhere('domain = :domain')
            ->andWhere('locale = :locale')
            ->setParameter('translationKey', $key)
            ->setParameter('domain', $domain)
            ->setParameter('locale', $locale);

        $result = $connection->fetchAssociative(
            $qb->getSQL(),
            $qb->getParameters(),
            $qb->getParameterTypes()
        );

        return (false === $result) ? null : $this->mapper->fromDb($result);
    }

    public function deleteByKeyLocaleDomain(string $key, string $locale, string $domain): void
    {
        $connection = $this->getConnection();

        $connection->transactional(function () use ($connection, $key, $locale, $domain): void {
            $qb = $connection->createQueryBuilder()
                ->delete(TranslationTable::NAME)
                ->where('translation_key = :translationKey')
                ->andWhere('domain = :domain')
                ->andWhere('locale = :locale')
                ->setParameter('translationKey', $key)
                ->setParameter('domain', $domain)
                ->setParameter('locale', $locale);

            $qb->executeStatement();
        });
    }

    public function create(Translation $translation): void
    {
        $connection = $this->getConnection();

        $connection->transactional(function () use ($connection, $translation): void {
            $connection->insert(
                TranslationTable::NAME,
                $this->mapper->toDb($translation),
                TranslationTable::columnTypes()
            );
        });
    }

    public function update(Translation $translation): void
    {
        $connection = $this->getConnection();

        $connection->transactional(function () use ($connection, $translation): void {
            $connection->update(
                TranslationTable::NAME,
                $this->mapper->toDb($translation),
                [
                    'id' => $translation->getId(),
                ],
                TranslationTable::columnTypes()
            );
        });
    }

    public function deleteById(int $id): void
    {
        $connection = $this->getConnection();

        $connection->transactional(function () use ($connection, $id): void {
            $connection->delete(
                TranslationTable::NAME,
                [
                    'id' => $id,
                ],
            );
        });
    }

    private function getConnection(): Connection
    {
        return $this->manager->getConnection();
    }
}
