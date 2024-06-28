<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Infrastructure\Psr;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationCreatedEvent;
use Tailr\SuluTranslationsBundle\Infrastructure\Psr\PsrEventDispatcher;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class PsrEventDispatcherTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    public function it_can_dispatch_domain_events_via_psr_dispatcher(): void
    {
        $frameworkDispatcher = $this->prophesize(EventDispatcherInterface::class);
        $event = new TranslationCreatedEvent(Translations::create());

        $frameworkDispatcher
            ->dispatch($event)
            ->willReturn($event)
            ->shouldBeCalled();
        $dispatcher = new PsrEventDispatcher($frameworkDispatcher->reveal());

        self::assertSame($event, $dispatcher->dispatch($event));
    }
}
