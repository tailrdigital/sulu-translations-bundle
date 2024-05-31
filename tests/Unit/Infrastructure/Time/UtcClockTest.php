<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Infrastructure\Time;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\MockClock;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Time\UtcClock;

class UtcClockTest extends TestCase
{
    /** @test */
    public function it_can_return_the_current_datetime(): void
    {
        $clock = new UtcClock(
            new MockClock($now = new \DateTimeImmutable())
        );

        self::assertEquals($now, $clock->now());
    }
}
