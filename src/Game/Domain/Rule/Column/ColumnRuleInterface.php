<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Column;

use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Model\Game;

interface ColumnRuleInterface
{
    public function supports(ScoreEntryColumnEnum $column): bool;

    public function validate(
        ScoreEntryColumnEnum $column,
        ScoreEntryFieldEnum  $row,
        int                  $participantId,
        Game                 $game
    ): bool;
}
