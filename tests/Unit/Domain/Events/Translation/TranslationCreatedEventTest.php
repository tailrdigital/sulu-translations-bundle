<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Events\Translation;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationCreatedEvent;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class TranslationCreatedEventTest extends TestCase
{
    /** @test */
    public function it_can_create_an_event(): void
    {
        $event = new TranslationCreatedEvent($translation = Translations::create());

        self::assertInstanceOf(TranslationCreatedEvent::class, $event);
        self::assertSame($translation, $event->translation);
    }
}
