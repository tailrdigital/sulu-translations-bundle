<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Events\Translation;

use Tailr\SuluTranslationsBundle\Domain\Events\DomainEvent;

class TranslationsExportedEvent implements DomainEvent
{
    public function __construct(
        public readonly string $result,
        public readonly \DateTimeImmutable $exportedAt,
    ) {
    }
}
