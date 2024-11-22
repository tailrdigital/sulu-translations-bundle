<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Command;

use Tailr\SuluTranslationsBundle\Domain\Action\ExportAction;
use Tailr\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationsExportedEvent;
use Tailr\SuluTranslationsBundle\Domain\Time\Clock;

class ExportHandler
{
    public function __construct(
        private readonly Clock $clock,
        private readonly ExportAction $exportAction,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(ExportCommand $command): string
    {
        $result = ($this->exportAction)();
        $this->eventDispatcher->dispatch(new TranslationsExportedEvent($result, $this->clock->now()));

        return $result;
    }
}
