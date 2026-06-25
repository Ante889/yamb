<?php

declare(strict_types=1);

namespace App\Game\Application\Query\Room;


use App\Shared\Domain\Paginator\Value\Filter;

final readonly class GetRoomsQuery
{
    public function __construct(
        public Filter $filter
    )
    {
    }
}
