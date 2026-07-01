<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game\Join;

final readonly class JoinWithGameIdCommand
{
    public function __construct(
        public int $gameId,
    )
    {
    }
}
