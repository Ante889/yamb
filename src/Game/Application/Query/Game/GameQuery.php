<?php

declare(strict_types=1);

namespace App\Game\Application\Query\Game;

final readonly class GameQuery
{
    public function __construct(
        public ?int $gameId
    )
    {
    }
}
