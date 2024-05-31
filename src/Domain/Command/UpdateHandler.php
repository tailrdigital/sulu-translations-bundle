<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Command;

use Tailr\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationUpdatedEvent;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Domain\Time\Clock;

class UpdateHandler
{
    public function __construct(
        private readonly Clock $clock,
        private readonly TranslationRepository $repository,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(UpdateCommand $command): void
    {
        $translation = $this->repository->findById($command->id);
        $this->repository->save(
            $translation->patch(
                $command->translationMessage,
                $this->clock->now()
            )
        );

        $this->eventDispatcher->dispatch(new TranslationUpdatedEvent($translation));
    }
}
