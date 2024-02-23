<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider;

use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Tailr\SuluTranslationsBundle\Domain\Model\Translation;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;

class Writer
{
    public function __construct(
        private readonly ClockInterface $clock,
        private readonly TranslationRepository $repository,
    ) {
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
                foreach ($messagesMap as $translationKey => $translationMessage) {
                    $translation = $this->repository->findByKeyLocaleDomain($translationKey, $locale, $domain);
                    if (null !== $translation) {
                        continue;
                    }
                    $this->repository->save(new Translation(
                        $locale,
                        $domain,
                        $translationKey,
                        $translationMessage,
                        $this->clock->now(),
                    ));
                }
            }
        }
    }
}
