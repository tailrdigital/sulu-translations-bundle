<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Translation\Provider\Dsn;
use Symfony\Component\Translation\Provider\TranslationProviderCollection;

class DatabaseConnectionManager
{
    public function __construct(
        private readonly TranslationProviderCollection $translationProviderCollection,
        private readonly ManagerRegistry $doctrineManagerRegistry,
    ) {
    }

    /** @psalm-suppress MoreSpecificReturnType, LessSpecificReturnStatement */
    public function getConnection(): Connection
    {
        try {
            return $this->doctrineManagerRegistry->getConnection($this->getConnectionName());
        } catch (\InvalidArgumentException $e) {
            throw new \RuntimeException('Doctrine connection not found. Please check the DSN configuration of your database translator provider.', previous: $e);
        }
    }

    private function getConnectionName(): string
    {
        return (new Dsn((string) $this->translationProviderCollection->get('database')))->getHost();
    }
}
