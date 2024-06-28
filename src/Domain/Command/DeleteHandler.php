<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Command;

use Tailr\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationDeletedEvent;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Domain\Time\Clock;

class DeleteHandler
{
    public function __construct(
        private readonly Clock $clock,
        private readonly TranslationRepository $repository,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(DeleteCommand $command): void
    {
        $this->repository->deleteByKeyLocaleDomain(
            $command->translationKey,
            $command->locale,
            $command->domain
        );

        $this->eventDispatcher->dispatch(
            new TranslationDeletedEvent(
                $command->translationKey,
                $command->locale,
                $command->domain,
                $this->clock->now(),
            )
        );
    }
}
