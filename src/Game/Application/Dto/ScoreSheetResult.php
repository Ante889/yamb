<?php

declare(strict_types=1);

namespace App\Game\Application\Dto;

final readonly class ScoreSheetResult
{
    public function __construct(
        public array  $grid,
        public array  $upperTotals,
        public array  $minMaxTotals,
        public array  $downTotals,
        public array  $grandTotals,
        public int    $diceNumber,
        public array  $dices,
        public int    $throwNumber,
        public string $gameType
    )
    {
    }
}
