<?php

declare(strict_types=1);

namespace App\Shared\Domain\Paginator\Value;

final readonly class Filter
{
    public function __construct(
        private int $limit,
        private int $offset,
        private int $page,
    )
    {
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
