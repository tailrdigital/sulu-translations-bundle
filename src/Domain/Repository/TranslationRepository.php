<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Repository;

use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Query\SearchCriteria;

interface TranslationRepository
{
    public function findById(int $id): Translation;

    /**
     * @return Translation[]
     */
    public function findByCriteria(SearchCriteria $criteria): array;

    /**
     * @return Translation[]
     */
    public function findAllByLocaleDomain(string $locale, string $domain): array;

    public function findByKeyLocaleDomain(string $key, string $locale, string $domain): ?Translation;

    public function create(Translation $translation): void;

    public function update(Translation $translation): void;

    public function deleteByKeyLocaleDomain(string $key, string $locale, string $domain): void;

    public function deleteById(int $id): void;
}
