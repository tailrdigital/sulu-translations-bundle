<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Events\Translation;

use Tailr\SuluTranslationsBundle\Domain\Events\DomainEvent;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;

class TranslationCreatedEvent implements DomainEvent
{
    public function __construct(
        public readonly Translation $translation,
    ) {
    }
}
