<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Command;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Tailr\SuluTranslationsBundle\Domain\Action\ExportAction;
use Tailr\SuluTranslationsBundle\Domain\Command\ExportCommand;
use Tailr\SuluTranslationsBundle\Domain\Command\ExportHandler;
use Tailr\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationsExportedEvent;
use Tailr\SuluTranslationsBundle\Domain\Time\Clock;

class ExportHandlerTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy|Clock $clock;
    private ObjectProphecy|ExportAction $exportAction;
    private ObjectProphecy|EventDispatcher $eventDispatcher;
    private ExportHandler $exportHandler;

    protected function setUp(): void
    {
        $this->clock = $this->prophesize(Clock::class);
        $this->exportAction = $this->prophesize(ExportAction::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcher::class);

        $this->exportHandler = new ExportHandler(
            $this->clock->reveal(),
            $this->exportAction->reveal(),
            $this->eventDispatcher->reveal()
        );
    }

    /** @test */
    public function it_can_export_all_database_translations(): void
    {

        $command = new ExportCommand();
        $this->clock->now()
            ->willReturn($now = new \DateTimeImmutable());
        $this->exportAction
            ->__invoke()
            ->willReturn($exportResult = 'Export result');
        $this->eventDispatcher
            ->dispatch(Argument::that(fn (TranslationsExportedEvent $event) => $event->result === $exportResult && $event->exportedAt === $now))
            ->shouldBeCalled();

        $result = $this->exportHandler->__invoke($command);
        self::assertSame($exportResult, $result);
    }
}
