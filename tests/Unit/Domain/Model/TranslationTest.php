<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Model;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;

class TranslationTest extends TestCase
{
    /** @test */
    public function it_can_create_a_translation(): void
    {
        $translation = new Translation(
            $locale = 'en',
            $domain = 'messages',
            $key = 'app.foo.bar',
            $translationValue = 'Foo Bar Value',
            $createdAt = new \DateTimeImmutable(),
        );

        self::assertSame($locale, $translation->getLocale());
        self::assertSame($domain, $translation->getDomain());
        self::assertSame($key, $translation->getKey());
        self::assertSame($translationValue, $translation->getTranslation());
        self::assertSame($createdAt, $translation->getCreatedAt());
        self::assertNull($translation->getUpdatedAt());
    }

    /** @test */
    public function it_can_patch_a_translation(): void
    {
        $translation = new Translation(
            $locale = 'en',
            $domain = 'messages',
            $key = 'app.foo.bar',
            'Foo Bar Value',
            $createdAt = new \DateTimeImmutable(),
        );

        $translation->patch($updatedTranslationValue = 'Updated value', $updatedAt = new \DateTimeImmutable());

        self::assertSame($locale, $translation->getLocale());
        self::assertSame($domain, $translation->getDomain());
        self::assertSame($key, $translation->getKey());
        self::assertSame($updatedTranslationValue, $translation->getTranslation());
        self::assertSame($createdAt, $translation->getCreatedAt());
        self::assertSame($updatedAt, $translation->getUpdatedAt());
    }
}
