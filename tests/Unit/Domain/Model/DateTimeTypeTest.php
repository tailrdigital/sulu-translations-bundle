<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Model;

use PHPUnit\Framework\TestCase;
use Psl\Type\TypeInterface;
use Tailr\SuluTranslationsBundle\Domain\Model\DateTimeType;

final class DateTimeTypeTest extends TestCase
{
    /** @test */
    public function it_can_return_non_empty_string_and_instance_of_date_time_immutable(): void
    {
        $type = DateTimeType::type();
        $this->assertInstanceOf(TypeInterface::class, $type);
    }

    /** @test */
    public function it_should_create_date_time_immutable_from_valid_string(): void
    {
        $dateTimeString = '2024-11-21 12:00:00';
        $dateTime = DateTimeType::fromString($dateTimeString);
        $this->assertInstanceOf(\DateTimeImmutable::class, $dateTime);
        $this->assertEquals($dateTimeString, $dateTime->format('Y-m-d H:i:s'));
    }
}
