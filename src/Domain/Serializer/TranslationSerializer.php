<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Serializer;

use Tailr\SuluTranslationsBundle\Domain\Model\Translation;

class TranslationSerializer
{
    public function __invoke(Translation $translation): array
    {
        return [
            'id' => $translation->getId(),
            'key' => $translation->getKey(),
            'translation' => $translation->getTranslation(),
            'domain' => $translation->getDomain(),
            'locale' => $translation->getLocale(),
        ];
    }
}
