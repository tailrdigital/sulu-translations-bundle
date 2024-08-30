<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Mapper;

class DateTimeImmutableMapper
{
    public static function map(string $value): \DateTimeImmutable
    {
        $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);

        if (!$dateTime) {
            throw new \InvalidArgumentException('Invalid date time format');
        }

        return $dateTime;
    }
}
