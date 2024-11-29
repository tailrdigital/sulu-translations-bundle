<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Infrastructure\Symfony\Translation\Provider;

use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\TranslatorBag;
use Tailr\SuluTranslationsBundle\Domain\Repository\TranslationRepository;

use function Psl\Type\string;
use function Psl\Type\vec;

class Loader
{
    public function __construct(private readonly TranslationRepository $repository)
    {
    }

    public function execute(array $domains, array $locales): TranslatorBag
    {
        $domains = vec(string())->coerce($domains);
        $locales = vec(string())->coerce($locales);

        $translatorBag = new TranslatorBag();
        foreach ($domains as $domain) {
            foreach ($locales as $locale) {
                $translatorBag->addCatalogue($this->generateMessageCatalogue($domain, $locale));
            }
        }

        return $translatorBag;
    }

    private function generateMessageCatalogue(string $domain, string $locale): MessageCatalogue
    {
        $translations = [];
        foreach ($this->repository->findAllByLocaleDomain($locale, $domain) as $translation) {
            $translations[$translation->getTranslationKey()] = $translation->getTranslation();
        }

        return new MessageCatalogue($locale, [$domain => $translations]);
    }
}
