<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Export;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Tailr\SuluTranslationsBundle\Domain\Command\ExportHandlerInterface;
use Tailr\SuluTranslationsBundle\Domain\Exception\ExportFailedException;

class CliExportHandler implements ExportHandlerInterface
{
    public function __construct(private readonly string $projectDir)
    {
    }

    public function __invoke(array $locales = null, array $domains = null): string
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
                ...(is_array($locales)) ? ['--locales', ...$locales] : [],
                ...(is_array($domains)) ? ['--domains', ...$domains] : [],
            ], timeout: 180);
            $process->setWorkingDirectory($this->projectDir);
            $process->mustRun();

            return $process->getOutput();
        } catch (ProcessFailedException $exception) {
            throw ExportFailedException::create($exception);
        }
    }
}
