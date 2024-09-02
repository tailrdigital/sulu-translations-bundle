<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Tests\Unit\Domain\Model;

use PHPUnit\Framework\TestCase;
use Tailr\SuluTranslationsBundle\Domain\Model\TranslationCollection;
use Tailr\SuluTranslationsBundle\Domain\Model\TranslationList;
use Tailr\SuluTranslationsBundle\Tests\Fixtures\Translations;

class TranslationListTest extends TestCase
{
    private TranslationCollection $collection;
    private TranslationList $list;

    protected function setUp(): void
    {
        $this->list = new TranslationList(
            $this->collection = new TranslationCollection(
                Translations::create('Foo')
            ),
            1
        );
    }

    /** @test */
    public function it_has_collection(): void
    {
        self::assertSame($this->collection, $this->list->translationCollection());
        self::assertCount(1, $this->list->translationCollection());
    }

    /** @test */
    public function it_has_a_total_count(): void
    {
        self::assertSame(1, $this->list->totalCount());
    }
}
