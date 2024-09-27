<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Translation\Provider\Dsn;
use Symfony\Component\Translation\Provider\TranslationProviderCollection;

use function Psl\Type\instance_of;

class DatabaseConnectionManager
{
    public function __construct(
        private readonly TranslationProviderCollection $providerCollection,
        private readonly ManagerRegistry $doctrineManagerRegistry,
    ) {
    }

    public function getConnection(): Connection
    {
        try {
            $connection = $this->doctrineManagerRegistry->getConnection($this->getConnectionName());

            return instance_of(Connection::class)->assert($connection);
        } catch (\InvalidArgumentException $e) {
            throw new \RuntimeException('Doctrine connection not found. Please check the DSN configuration of your database translator provider.', previous: $e);
        }
    }

    private function getConnectionName(): string
    {
        return (new Dsn((string) $this->providerCollection->get('database')))->getHost();
    }
}
