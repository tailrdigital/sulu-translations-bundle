<?php

declare(strict_types=1);

namespace Tailr\SuluTranslationsBundle\Domain\Query;

final class SearchCriteria
{
    /**
     * @param string $searchString
     * @param array<string, mixed> $filters
     * @param string|null $sortColumn
     * @param string|null $sortDirection
     * @param int $offset
     * @param int $limit
     */
    public function __construct(
        private readonly string $searchString,
        private readonly array $filters,
        private readonly ?string $sortColumn,
        private readonly ?string $sortDirection,
        private readonly int $offset,
        private readonly int $limit,
    ) {
    }

    public function searchString(): string
    {
        return $this->searchString;
    }

    /**
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        return $this->filters;
    }

    public function sortColumn(): ?string
    {
        return $this->sortColumn;
    }

    public function sortDirection(): ?string
    {
        return $this->sortDirection;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function limit(): int
    {
        return $this->limit;
    }
}
