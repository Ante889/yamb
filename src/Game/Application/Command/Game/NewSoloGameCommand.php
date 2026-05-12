<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

final readonly class NewSoloGameCommand
{
    public function __construct(
        public int $numberOfDice
    )
    {
    }
}
