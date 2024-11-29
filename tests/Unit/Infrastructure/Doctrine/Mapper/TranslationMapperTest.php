<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Infrastructure\Doctrine\Mapper;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Mapper\TranslationMapper;

class TranslationMapperTest extends TestCase
{
    /** @test */
    public function it_can_map_to_db(): void
    {
        $translation = Translation::create(
            $locale = 'en',
            $domain = 'messages',
            $key = 'app.foo.bar',
            $translationValue = 'Foo Bar Value',
            $createdAt = new \DateTimeImmutable(),
        );

        $translationMapper = new TranslationMapper();
        $mappedTranslation = $translationMapper->toDb($translation);

        self::assertSame($locale, $mappedTranslation['locale']);
        self::assertSame($domain, $mappedTranslation['domain']);
        self::assertSame($key, $mappedTranslation['translation_key']);
        self::assertSame($translationValue, $mappedTranslation['translation']);
        self::assertSame($createdAt, $mappedTranslation['created_at']);
        self::assertNull($mappedTranslation['updated_at']);
    }

    /** @test */
    public function it_can_map_from_db(): void
    {
        $data = [
            'tailr_translations_id' => $id = 1,
            'tailr_translations_locale' => $locale = 'en',
            'tailr_translations_domain' => $domain = 'messages',
            'tailr_translations_translation_key' => $key = 'app.foo.bar',
            'tailr_translations_translation' => $translationValue = 'Foo Bar Value',
            'tailr_translations_created_at' => $createdAt = '2021-01-01 00:00:00',
            'tailr_translations_updated_at' => $updatedAt = '2021-01-01 10:00:00',
        ];

        $translationMapper = new TranslationMapper();
        $translation = $translationMapper->fromDb($data);

        self::assertSame($id, $translation->getId());
        self::assertSame($locale, $translation->getLocale());
        self::assertSame($domain, $translation->getDomain());
        self::assertSame($key, $translation->getTranslationKey());
        self::assertSame($translationValue, $translation->getTranslation());
        self::assertSame($createdAt, $translation->getCreatedAt()->format('Y-m-d H:i:s'));
        self::assertSame($updatedAt, $translation->getUpdatedAt()->format('Y-m-d H:i:s'));
    }
}
