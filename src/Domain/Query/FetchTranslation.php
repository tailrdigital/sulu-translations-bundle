<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Query;

use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;

class FetchTranslation
{
    public function __construct(
        private readonly TranslationRepository $repository
    ) {
    }

    public function __invoke(int $id): Translation
    {
        return $this->repository->findById($id);
    }
}
