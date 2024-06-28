<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Command;

class UpdateCommand
{
    public function __construct(
        public int $id,
        public string $translationMessage
    ) {
    }
}
