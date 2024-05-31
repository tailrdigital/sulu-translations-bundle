<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Events\Translation;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationUpdatedEvent;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class TranslationUpdatedEventTest extends TestCase
{
    /** @test */
    public function it_can_create_an_event(): void
    {
        $event = new TranslationUpdatedEvent($translation = Translations::create());

        self::assertInstanceOf(TranslationUpdatedEvent::class, $event);
        self::assertSame($translation, $event->translation);
    }
}
