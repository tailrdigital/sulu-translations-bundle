<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Serializer;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Serializer\TranslationSerializer;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class TranslationSerializerTest extends TestCase
{
    /** @test */
    public function it_can_serialize_a_translation(): void
    {
        $translation = Translations::withId($id = 1, Translations::create());
        $serializer = new TranslationSerializer();

        self::assertSame(
            [
                'id' => $translation->getId(),
                'key' => $translation->getKey(),
                'translation' => $translation->getTranslation(),
                'domain' => $translation->getDomain(),
                'locale' => $translation->getLocale(),
            ],
            ($serializer)($translation)
        );
    }
}
