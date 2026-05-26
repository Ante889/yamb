<?php

declare(strict_types=1);

namespace App\Game\Domain\Event;

final readonly class GameCanceled
{
    public function __construct(
        public string $gameId
    ) {
    }
}
