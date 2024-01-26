<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;

class DoctrineTranslationRepository implements TranslationRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function findById(int $id): ?Translation
    {
        return $this->repository()->find($id);
    }

    public function findAllByLocaleDomain(string $locale, string $domain): array
    {
        $qb = $this->repository()->createQueryBuilder('translation');
        $qb->where('translation.domain = :domain')
            ->andWhere('translation.locale = :locale')
            ->setParameter('domain', $domain)
            ->setParameter('locale', $locale);

        return $qb->getQuery()->getResult();
    }

    public function findByKeyLocaleDomain(string $key, string $locale, string $domain): ?Translation
    {
        $qb = $this->repository()->createQueryBuilder('translation');

        return $qb->where('translation.key = :key')
            ->andWhere('translation.domain = :domain')
            ->andWhere('translation.locale = :locale')
            ->setParameter('key', $key)
            ->setParameter('domain', $domain)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function removeByKeyLocaleDomain(string $key, string $locale, string $domain): void
    {
        $this->repository()->createQueryBuilder('translation')
            ->delete()
            ->where('translation.key = :key')
            ->andWhere('translation.domain = :domain')
            ->andWhere('translation.locale = :locale')
            ->setParameter('key', $key)
            ->setParameter('domain', $domain)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->execute();
    }

    public function save(Translation $translation): void
    {
        $this->entityManager->persist($translation);
        $this->entityManager->flush();
    }

    public function removeById(int $id): void
    {
        if (!$translation = $this->findById($id)) {
            return;
        }

        $this->entityManager->remove($translation);
        $this->entityManager->flush();
    }

    /**
     * @return EntityRepository<Translation>
     */
    protected function repository(): EntityRepository
    {
        return $this->entityManager->getRepository(Translation::class);
    }
}
