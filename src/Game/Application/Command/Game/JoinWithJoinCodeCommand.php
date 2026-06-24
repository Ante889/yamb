<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

final readonly class JoinWithJoinCodeCommand
{
    public function __construct(
        public string $joinCode,
    )
    {
    }
}
