<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Presentation\Console;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ConnectionRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tailr\SuluTranslationsBundle\Infrastructure\Doctrine\Dbal\SetupTranslationsTable;

use function Psl\Type\instance_of;

#[AsCommand(name: 'tailr:translations:setup', description: 'It will update the database schema for the translations table.')]
class SetupTranslationsTableCommand extends Command
{
    public function __construct(
        private readonly ConnectionRegistry $connectionRegistry,
        private readonly SetupTranslationsTable $setup,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $conn = $this->connectionRegistry->getConnection('tailr_translations');

        ($this->setup)(instance_of(Connection::class)->assert($conn));

        $style->info('Finished!');

        return Command::SUCCESS;
    }
}
