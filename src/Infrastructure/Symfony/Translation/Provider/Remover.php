<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider;

use Symfony\Component\Translation\TranslatorBagInterface;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;

class Remover
{
    public function __construct(private readonly TranslationRepository $repository)
    {
    }

    public function execute(TranslatorBagInterface $translatorBag): void
    {
        foreach ($translatorBag->getCatalogues() as $catalogue) {
            $locale = $catalogue->getLocale();
            foreach ($catalogue->all() as $domain => $messagesMap) {
                foreach (array_keys($messagesMap) as $key) {
                    $this->repository->removeByKeyLocaleDomain(
                        $key,
                        $locale,
                        $domain
                    );
                }
            }
        }
    }
}
