<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Command\DeleteCommand;

class DeleteCommandTest extends TestCase
{
    /** @test */
    public function it_can_create_a_command(): void
    {
        $translationKey = 'key';
        $locale = 'en';
        $domain = 'domain';

        $command = new DeleteCommand($translationKey, $locale, $domain);

        self::assertInstanceOf(DeleteCommand::class, $command);
        self::assertSame($translationKey, $command->translationKey);
        self::assertSame($locale, $command->locale);
        self::assertSame($domain, $command->domain);
    }
}
