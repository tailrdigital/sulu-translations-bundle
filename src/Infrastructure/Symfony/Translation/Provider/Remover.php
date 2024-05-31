<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider;

use Symfony\Component\Translation\TranslatorBagInterface;
use Tailr\SuluTranslationsBundle\Domain\Command\DeleteCommand;
use Tailr\SuluTranslationsBundle\Domain\Command\DeleteHandler;

class Remover
{
    public function __construct(private readonly DeleteHandler $handler)
    {
    }

    public function execute(TranslatorBagInterface $translatorBag): void
    {
        foreach ($translatorBag->getCatalogues() as $catalogue) {
            $locale = $catalogue->getLocale();
            /**
             * @var string $domain
             * @var array<string, string> $messagesMap
             */
            foreach ($catalogue->all() as $domain => $messagesMap) {
                foreach (array_keys($messagesMap) as $key) {
                    ($this->handler)(new DeleteCommand($key, $locale, $domain));
                }
            }
        }
    }
}
