<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Presentation\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Schema\SetupTranslationsTable;

#[AsCommand(name: 'tailr:translations:setup', description: 'It will update the database schema for the translations table.')]
class SetupTranslationsTableCommand extends Command
{
    public function __construct(
        private readonly SetupTranslationsTable $setup,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $style->writeln('Setting up the translations table...');
        $this->setup->execute();
        $style->info('Finished! The translation table was created or updated.');

        return Command::SUCCESS;
    }
}
