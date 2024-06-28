<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Events\Translation;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationDeletedEvent;

class TranslationDeletedEventTest extends TestCase
{
    /** @test */
    public function it_can_create_an_event(): void
    {
        $event = new TranslationDeletedEvent(
            $key = 'key',
            $locale = 'en',
            $domain = 'domain',
            $removedAt = new \DateTimeImmutable(),
        );

        self::assertInstanceOf(TranslationDeletedEvent::class, $event);
        self::assertSame($key, $event->translationKey);
        self::assertSame($locale, $event->locale);
        self::assertSame($domain, $event->domain);
        self::assertSame($removedAt, $event->removedAt);
    }
}
