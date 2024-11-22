<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Events\Translation;

use Tailr\SuluTranslationsBundle\Domain\Events\DomainEvent;

class TranslationDeletedEvent implements DomainEvent
{
    public function __construct(
        public string $translationKey,
        public string $locale,
        public string $domain,
        public \DateTimeImmutable $removedAt,
    ) {
    }
}
