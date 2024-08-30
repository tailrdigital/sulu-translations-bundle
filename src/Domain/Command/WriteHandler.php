<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Command;

use Tailr\SuluTranslationsBundle\Domain\Events\EventDispatcher;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationCreatedEvent;
use Tailr\SuluTranslationsBundle\Domain\Events\Translation\TranslationUpdatedEvent;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;
use Tailr\SuluTranslationsBundle\Domain\Time\Clock;

class WriteHandler
{
    public function __construct(
        private readonly Clock $clock,
        private readonly TranslationRepository $repository,
        private readonly EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(WriteCommand $command): void
    {
        $translation = $this->repository->findByKeyLocaleDomain($command->translationKey, $command->locale, $command->domain);

        if (null !== $translation) {
            $translation->patch($command->translationMessage, $this->clock->now());
            $this->repository->update($translation);
            $this->eventDispatcher->dispatch(new TranslationUpdatedEvent($translation));

            return;
        }

        $this->repository->create($newTranslation = Translation::create(
            $command->locale,
            $command->domain,
            $command->translationKey,
            $command->translationMessage,
            $this->clock->now(),
        ));

        $this->eventDispatcher->dispatch(new TranslationCreatedEvent($newTranslation));
    }
}
