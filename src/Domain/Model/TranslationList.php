<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Model;

final class TranslationList
{
    public function __construct(
        private readonly TranslationCollection $translationCollection,
        private readonly int $totalCount
    ) {
    }

    public function translationCollection(): TranslationCollection
    {
        return $this->translationCollection;
    }

    public function totalCount(): int
    {
        return $this->totalCount;
    }
}
