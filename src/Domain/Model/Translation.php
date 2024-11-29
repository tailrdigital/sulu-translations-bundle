<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Model;

use function Psl\invariant;

final class Translation
{
    private function __construct(
        private ?int $id,
        private string $locale,
        private string $domain,
        private string $translationKey,
        private string $translation,
        private \DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $updatedAt,
    ) {
    }

    public static function create(
        string $locale,
        string $domain,
        string $translationKey,
        string $translation,
        \DateTimeImmutable $createdAt,
    ): self {
        return new self(null, $locale, $domain, $translationKey, $translation, $createdAt, null);
    }

    public static function load(
        int $id,
        string $locale,
        string $domain,
        string $translationKey,
        string $translation,
        \DateTimeImmutable $createdAt,
        ?\DateTimeImmutable $updatedAt,
    ): self {
        return new self($id, $locale, $domain, $translationKey, $translation, $createdAt, $updatedAt);
    }

    public function getId(): int
    {
        invariant(null !== $this->id, 'Please save model before selecting the ID.');

        return $this->id;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getTranslationKey(): string
    {
        return $this->translationKey;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getCombinedIdAndTranslation(): string
    {
        return $this->getId().';'.$this->getTranslation();
    }

    public function patch(string $translation, \DateTimeImmutable $updatedAt): self
    {
        $this->translation = $translation;
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
