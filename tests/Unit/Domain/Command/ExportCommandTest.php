<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Command\ExportCommand;

class ExportCommandTest extends TestCase
{
    /** @test */
    public function it_can_create_a_command(): void
    {
        $command = new ExportCommand();

        self::assertInstanceOf(ExportCommand::class, $command);
    }
}
