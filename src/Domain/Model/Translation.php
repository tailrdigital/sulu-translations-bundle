<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Model;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use function Psl\invariant;

#[ORM\Entity]
#[ORM\Table(name: 'tailr_translations')]
class Translation
{
    public const RESOURCE_KEY = 'tailr_translations';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 2, nullable: false)]
    private string $locale;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private string $domain;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $key;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $translation;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct(
        string $locale,
        string $domain,
        string $key,
        string $translation,
        \DateTimeImmutable $createdAt
    ) {
        $this->locale = $locale;
        $this->domain = $domain;
        $this->key = $key;
        $this->translation = $translation;
        $this->createdAt = $createdAt;
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

    public function getKey(): string
    {
        return $this->key;
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

    public function patch(string $translation, \DateTimeImmutable $updatedAt): self
    {
        $this->translation = $translation;
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
