<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\DBAL\Connection;
use Tailr\SuluTranslationsBundle\Domain\Exception\TranslationNotFoundException;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Query\SearchCriteria;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\DatabaseConnectionManager;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Mapper\TranslationMapper;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Schema\TranslationTable;

use function Psl\Str\lowercase;
use function Psl\Vec\map;

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

    public function findAllByLocaleDomain(string $locale, string $domain): array
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

        return map(
            $result,
            fn (array $row) => $this->mapper->fromDb($row)
        );
    }

    public function findByCriteria(SearchCriteria $criteria): array
    {
        $connection = $this->getConnection();

        $qb = $connection->createQueryBuilder();
        $qb->select(TranslationTable::selectColumns())
            ->from(TranslationTable::NAME);

        if ($search = $criteria->searchString()) {
            $qb->andWhere($qb->expr()->like('lower(translation)', ':search'))
                ->setParameter('search', '%'.lowercase($search).'%');
        }

        (null !== $criteria->sortColumn() && null !== $criteria->sortDirection())
            ? $qb->orderBy($criteria->sortColumn(), $criteria->sortDirection())
            : $qb->orderBy('created_at', 'DESC');

        $qb->setMaxResults($criteria->limit());
        $qb->setFirstResult($criteria->offset());

        $result = $connection->fetchAllAssociative(
            $qb->getSQL(),
            $qb->getParameters(),
            $qb->getParameterTypes()
        );

        return map(
            $result,
            fn (array $row) => $this->mapper->fromDb($row)
        );
    }

    public function findByKeyLocaleDomain(string $key, string $locale, string $domain): ?Translation
    {
        $connection = $this->getConnection();

        $qb = $connection->createQueryBuilder();
        $qb->select(TranslationTable::selectColumns())
            ->from(TranslationTable::NAME)
            ->where('key = :key')
            ->andWhere('domain = :domain')
            ->andWhere('locale = :locale')
            ->setParameter('key', $key)
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
                ->where('key = :key')
                ->andWhere('domain = :domain')
                ->andWhere('locale = :locale')
                ->setParameter('key', $key)
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
