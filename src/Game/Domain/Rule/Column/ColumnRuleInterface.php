<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Column;

use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;

interface ColumnRuleInterface
{
    public function supports(ScoreEntryColumnEnum $column): bool;

    public function validate(ScoreEntryFieldEnum $row, int $participantId): bool;
}
