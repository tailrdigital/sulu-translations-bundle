<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Time;

use Symfony\Component\Clock\ClockInterface;
use Tailr\SuluTranslationsBundle\Domain\Time\Clock;

class UtcClock implements Clock
{
    public function __construct(private readonly ClockInterface $clock)
    {
    }

    public function now(): \DateTimeImmutable
    {
        return $this->clock->now();
    }
}
