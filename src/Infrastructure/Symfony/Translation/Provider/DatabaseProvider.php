<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider;

use Symfony\Component\Translation\Provider\ProviderInterface;
use Symfony\Component\Translation\TranslatorBag;
use Symfony\Component\Translation\TranslatorBagInterface;

final class DatabaseProvider implements ProviderInterface
{
    public function __construct(
        private readonly Writer $writer,
        private readonly Loader $loader,
        private readonly Remover $remover,
    ) {
    }

    public function __toString(): string
    {
        return 'database://'.DatabaseProviderFactory::DATABASE_PROVIDER_NAME;
    }

    public function write(TranslatorBagInterface $translatorBag): void
    {
        $this->writer->execute($translatorBag);
    }

    public function read(array $domains, array $locales): TranslatorBag
    {
        return $this->loader->execute($domains, $locales);
    }

    public function delete(TranslatorBagInterface $translatorBag): void
    {
        $this->remover->execute($translatorBag);
    }
}
