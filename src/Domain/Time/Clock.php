<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Time;

interface Clock
{
    public function now(): \DateTimeImmutable;
}
