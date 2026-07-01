<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game\Create;

final readonly class NewGameCommand
{
    public function __construct(
        public int    $numberOfDice,
        public string $gameStatus,
        public string $gameType,
        public bool   $isPublic,
        public int    $minPlayer,
        public int    $maxPlayer,
        public bool   $isSolo
    )
    {
    }
}
