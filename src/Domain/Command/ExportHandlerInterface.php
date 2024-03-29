<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Command;

interface ExportHandlerInterface
{
    public function __invoke(array $locales = null, array $domains = null): string;
}
