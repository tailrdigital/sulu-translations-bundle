<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Translation\Exception\LogicException;
use Symfony\Component\Translation\Exception\UnsupportedSchemeException;
use Symfony\Component\Translation\Provider\AbstractProviderFactory;
use Symfony\Component\Translation\Provider\Dsn;

final class DatabaseProviderFactory extends AbstractProviderFactory
{
    public const PROVIDER_NAME = 'tailr_database';
    public const PROVIDER_DSN_SCHEME = 'database';

    public function __construct(
        private readonly Writer $writer,
        private readonly Loader $loader,
        private readonly Remover $remover,
        private readonly ManagerRegistry $connectionRegistry,
    ) {
    }

    public function create(Dsn $dsn): DatabaseProvider
    {
        if (self::PROVIDER_DSN_SCHEME !== $dsn->getScheme()) {
            throw new UnsupportedSchemeException($dsn, self::PROVIDER_NAME, $this->getSupportedSchemes());
        }

        $connectionName = $dsn->getHost();

        try {
            $this->connectionRegistry->getConnection($connectionName);

            return new DatabaseProvider($connectionName, $this->writer, $this->loader, $this->remover);
        } catch (\InvalidArgumentException $e) {
            throw new LogicException(
                sprintf(
                    'The DSN host should contain a valid Doctrine DBAL connection name. Wrong given %s.',
                    $connectionName,
                )
            );
        }
    }

    protected function getSupportedSchemes(): array
    {
        return [self::PROVIDER_DSN_SCHEME];
    }
}
