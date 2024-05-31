<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Export;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Tailr\SuluTranslationsBundle\Domain\Action\ExportAction;
use Tailr\SuluTranslationsBundle\Domain\Exception\ExportFailedException;

class CliExportAction implements ExportAction
{
    public function __construct(private readonly string $projectDir)
    {
    }

    public function __invoke(): string
    {
        try {
            $process = new Process([
                'php',
                'bin/console',
                '--no-interaction',
                'translation:pull',
                'database',
                '--format',
                'csv',
                '--force',
            ], timeout: 180);
            $process->setWorkingDirectory($this->projectDir);
            $process->mustRun();

            return $process->getOutput();
        } catch (ProcessFailedException $exception) {
            throw ExportFailedException::create($exception);
        }
    }
}
