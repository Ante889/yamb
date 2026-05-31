<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Row;

use App\Game\Domain\Enum\ScoreEntryFieldEnum;

interface RowRuleInterface
{
    public function supports(ScoreEntryFieldEnum $field): bool;

    public function calculate(ScoreEntryFieldEnum $field, array $dices): int;
}
