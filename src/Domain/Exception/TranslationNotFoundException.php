<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class TranslationNotFoundException extends NotFoundHttpException
{
    public static function withId(int $id): self
    {
        return new self(sprintf('Translation not found for ID %s.', $id));
    }
}
