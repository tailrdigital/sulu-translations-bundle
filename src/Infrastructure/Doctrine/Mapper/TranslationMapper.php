<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Mapper;

use Tailr\SuluTranslationsBundle\Domain\Model\DateTimeType;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;

use function Psl\Type\int;
use function Psl\Type\nullable;
use function Psl\Type\shape;
use function Psl\Type\string;

class TranslationMapper
{
    /**
     * @return array<string, mixed>
     */
    public function toDb(Translation $translation): array
    {
        return [
            'locale' => $translation->getLocale(),
            'domain' => $translation->getDomain(),
            'key' => $translation->getKey(),
            'translation' => $translation->getTranslation(),
            'created_at' => $translation->getCreatedAt(),
            'updated_at' => $translation->getUpdatedAt(),
        ];
    }

    public function fromDb(array $data): Translation
    {
        $parsedData = shape([
            'tailr_translations_id' => int(),
            'tailr_translations_locale' => string(),
            'tailr_translations_domain' => string(),
            'tailr_translations_key' => string(),
            'tailr_translations_translation' => string(),
            'tailr_translations_created_at' => DateTimeType::type(),
            'tailr_translations_updated_at' => nullable(DateTimeType::type()),
        ])->coerce($data);

        return Translation::load(
            $parsedData['tailr_translations_id'],
            $parsedData['tailr_translations_locale'],
            $parsedData['tailr_translations_domain'],
            $parsedData['tailr_translations_key'],
            $parsedData['tailr_translations_translation'],
            $parsedData['tailr_translations_created_at'],
            $parsedData['tailr_translations_updated_at'],
        );

    }
}
