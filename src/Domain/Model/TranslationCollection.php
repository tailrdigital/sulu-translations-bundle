<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Model;

/**
 * @psalm-immutable
 *
 * @template-implements \IteratorAggregate<int, Translation>
 */
class TranslationCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Translation[]
     */
    private array $translations;

    /**
     * @no-named-arguments
     */
    public function __construct(Translation ...$translations)
    {
        $this->translations = $translations;
    }

    /**
     * @return \Traversable<int, Translation>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->translations;
    }

    public function count(): int
    {
        return count($this->translations);
    }
}
