<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Fixtures;

use Tailr\SuluTranslationsBundle\Domain\Model\Translation;

class Translations
{
    public static function create(?string $key = null, ?string $value = null, ?\DateTimeImmutable $createdAt = null): Translation
    {
        return new Translation(
            'en',
            'messages',
            $key ?: 'app.foo.bar',
            $value ?: 'Foo Bar Value',
            $createdAt ?: new \DateTimeImmutable(),
        );
    }

    public static function withId(int $id, Translation $translation): Translation
    {
        return \Closure::bind(
            function ($id): Translation {
                $this->id = $id;

                return $this;
            },
            $translation,
            Translation::class
        )($id);
    }
}
