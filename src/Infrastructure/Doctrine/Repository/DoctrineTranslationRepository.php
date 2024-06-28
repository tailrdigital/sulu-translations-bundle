<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Tailr\SuluTranslationsBundle\Domain\Exception\TranslationNotFoundException;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;

class DoctrineTranslationRepository implements TranslationRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function findById(int $id): Translation
    {
        $translation = $this->repository()->find($id);
        if (null === $translation) {
            throw TranslationNotFoundException::withId($id);
        }

        return $translation;
    }

    public function findAllByLocaleDomain(string $locale, string $domain): array
    {
        $qb = $this->repository()->createQueryBuilder('translation');
        $qb->where('translation.domain = :domain')
            ->andWhere('translation.locale = :locale')
            ->setParameter('domain', $domain)
            ->setParameter('locale', $locale);

        /** @var Translation[] $result */
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function findByKeyLocaleDomain(string $key, string $locale, string $domain): ?Translation
    {
        $qb = $this->repository()->createQueryBuilder('translation');
        /** @var Translation|null $translation */
        $translation = $qb->where('translation.key = :key')
            ->andWhere('translation.domain = :domain')
            ->andWhere('translation.locale = :locale')
            ->setParameter('key', $key)
            ->setParameter('domain', $domain)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult();

        return $translation;
    }

    public function deleteByKeyLocaleDomain(string $key, string $locale, string $domain): void
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

    public function deleteById(int $id): void
    {
        $this->entityManager->remove($this->findById($id));
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
