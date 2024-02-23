<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Exception;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tailr\SuluTranslationsBundle\Domain\Exception\TranslationNotFoundException;

class TranslationNotFoundExceptionTest extends TestCase
{
    /** @test */
    public function it_make_a_translation_not_found_exception_with_id(): void
    {
        $exception = TranslationNotFoundException::withId(1);
        self::assertInstanceOf(TranslationNotFoundException::class, $exception);
        self::assertInstanceOf(NotFoundHttpException::class, $exception);
        self::assertSame('Translation not found for ID 1.', $exception->getMessage());
    }
}
