<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Psr;

use Psr\EventDispatcher\EventDispatcherInterface;
use Tailr\SuluTranslationsBundle\Domain\Events\DomainEvent;
use Tailr\SuluTranslationsBundle\Domain\Events\EventDispatcher;

use function Psl\Type\instance_of;

class PsrEventDispatcher implements EventDispatcher
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function dispatch(DomainEvent $event): DomainEvent
    {
        $dispatchedEvent = $this->eventDispatcher->dispatch($event);

        return instance_of(DomainEvent::class)->assert($dispatchedEvent);
    }
}
