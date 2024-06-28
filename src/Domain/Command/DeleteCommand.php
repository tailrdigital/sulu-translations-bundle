<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Command;

class DeleteCommand
{
    public function __construct(
        public string $translationKey,
        public string $locale,
        public string $domain,
    ) {
    }
}
