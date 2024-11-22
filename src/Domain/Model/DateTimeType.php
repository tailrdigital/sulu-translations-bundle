<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Model;

use DateTimeImmutable;
use Psl\Type\TypeInterface;

use function Psl\Type\converted;
use function Psl\Type\instance_of;
use function Psl\Type\non_empty_string;

final class DateTimeType
{
    /**
     * @psalm-return TypeInterface<DateTimeImmutable>
     */
    public static function type(): TypeInterface
    {
        return converted(
            non_empty_string(),
            instance_of(\DateTimeImmutable::class),
            self::fromString(...)
        );
    }

    public static function fromString(string $dateTime): \DateTimeImmutable
    {
        return new \DateTimeImmutable($dateTime);
    }
}
