<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Events\Translation;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationsExportedEvent;

class TranslationsExportedEventTest extends TestCase
{
    /** @test */
    public function it_can_create_an_event(): void
    {
        $event = new TranslationsExportedEvent(
            $result = 'result',
            $exportedAt = new \DateTimeImmutable(),
        );

        self::assertInstanceOf(TranslationsExportedEvent::class, $event);
        self::assertSame($result, $event->result);
        self::assertSame($exportedAt, $event->exportedAt);
    }
}
