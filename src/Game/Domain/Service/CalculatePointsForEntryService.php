<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;

final readonly class CalculatePointsForEntryService
{
    public function __construct(
    )
    {
    }

    public function calculate(array $dices,ScoreEntryColumnEnum $column, ScoreEntryFieldEnum $row): int
    {
        return 5;
    }
}
