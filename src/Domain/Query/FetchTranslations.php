<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Query;

use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;

class FetchTranslations
{
    public function __construct(
        private readonly TranslationRepository $repository,
    ) {
    }

    public function __invoke(SearchCriteria $criteria): array
    {
        return $this->repository->findByCriteria($criteria);
    }
}
