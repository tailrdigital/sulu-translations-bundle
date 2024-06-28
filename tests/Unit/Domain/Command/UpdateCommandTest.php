<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Command\UpdateCommand;

class UpdateCommandTest extends TestCase
{
    /** @test */
    public function it_can_create_a_command(): void
    {
        $id = 1;
        $translationValue = 'translation';

        $command = new UpdateCommand($id, $translationValue);

        self::assertInstanceOf(UpdateCommand::class, $command);
        self::assertSame($id, $command->id);
        self::assertSame($translationValue, $command->translationMessage);
    }
}
