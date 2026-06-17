<?php

declare(strict_types=1);

namespace App\Game\Application\Query\Leaderboard;

final readonly class LeaderboardSoloQuery
{
    public function __construct(
        public ?string $type
    )
    {
    }
}
