<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Action;

use Tailr\SuluTranslationsBundle\Domain\Exception\ExportFailedException;

interface ExportAction
{
    /**
     * @throws ExportFailedException
     *
     * @return string
     */
    public function __invoke(): string;
}
