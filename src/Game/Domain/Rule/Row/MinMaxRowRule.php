<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Row;

use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Exception\EnterNewEntryException;

final readonly class MinMaxRowRule implements RowRuleInterface
{
    public function supports(ScoreEntryFieldEnum $field): bool
    {
        return in_array($field, [
            ScoreEntryFieldEnum::MIN,
            ScoreEntryFieldEnum::MAX,
        ], true);
    }

    public function calculate(ScoreEntryFieldEnum $field, array $dices): int
    {
        return match ($field) {
            ScoreEntryFieldEnum::MIN => $this->calculateMin($dices),
            ScoreEntryFieldEnum::MAX => $this->calculateMax($dices),
            default => throw EnterNewEntryException::notPossibleToEnterInThisField()
        };
    }

    private function calculateMin(array $dices): int
    {
        sort($dices);

        return array_sum(array_slice($dices, 0, 5));
    }

    private function calculateMax(array $dices): int
    {
        rsort($dices);

        return array_sum(array_slice($dices, 0, 5));
    }
}
