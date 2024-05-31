<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Infrastructure\Translation\Provider;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Tailr\SuluTranslationsBundle\Domain\Command\WriteCommand;
use Tailr\SuluTranslationsBundle\Domain\Command\WriteHandler;
use Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider\Writer;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\TranslationBags;

class WriterTest extends TestCase
{
    use ProphecyTrait;

    private WriteHandler|ObjectProphecy $handler;
    private Writer $writer;

    protected function setUp(): void
    {
        $this->handler = $this->prophesize(WriteHandler::class);

        $this->writer = new Writer(
            $this->handler->reveal(),
        );
    }

    /** @test */
    public function it_can_write_new_or_updated_translations_via_write_handler(): void
    {
        $translationBag = TranslationBags::simple();

        $this->handler->__invoke(Argument::that(fn (WriteCommand $command) => 'app.foo' === $command->translationKey
            && 'en' === $command->locale
            && 'messages' === $command->domain
            && 'Foo' === $command->translationMessage))
            ->shouldBeCalledOnce();

        $this->writer->execute($translationBag);
    }
}
