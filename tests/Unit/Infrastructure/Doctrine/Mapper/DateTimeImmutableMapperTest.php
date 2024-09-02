<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Infrastructure\Doctrine\Mapper;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Mapper\DateTimeImmutableMapper;

class DateTimeImmutableMapperTest extends TestCase
{
    /** @test */
    public function it_can_map_a_string_to_a_datetime_immutable(): void
    {
        $dateTime = DateTimeImmutableMapper::map($dateTimeValue = '2021-01-01 21:55:10');

        self::assertSame($dateTimeValue, $dateTime->format('Y-m-d H:i:s'));
    }

    /** @test */
    public function it_throws_an_exception_when_the_date_time_format_is_invalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date time format');

        DateTimeImmutableMapper::map('2021-01-01');
    }
}
