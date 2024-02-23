<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider;

use Symfony\Component\Translation\Exception\LogicException;
use Symfony\Component\Translation\Exception\UnsupportedSchemeException;
use Symfony\Component\Translation\Provider\AbstractProviderFactory;
use Symfony\Component\Translation\Provider\Dsn;

final class DatabaseProviderFactory extends AbstractProviderFactory
{
    public const DATABASE_PROVIDER_NAME = 'tailr_translations';

    public function __construct(
        private readonly Writer $writer,
        private readonly Loader $loader,
        private readonly Remover $remover,
    ) {
    }

    public function create(Dsn $dsn): DatabaseProvider
    {
        if ('database' !== $dsn->getScheme()) {
            throw new UnsupportedSchemeException($dsn, 'database', $this->getSupportedSchemes());
        }

        if (self::DATABASE_PROVIDER_NAME !== $dsn->getHost()) {
            throw new LogicException(sprintf('The DSN should contain %s as name.', self::DATABASE_PROVIDER_NAME));
        }

        return new DatabaseProvider($this->writer, $this->loader, $this->remover);
    }

    protected function getSupportedSchemes(): array
    {
        return ['database'];
    }
}
