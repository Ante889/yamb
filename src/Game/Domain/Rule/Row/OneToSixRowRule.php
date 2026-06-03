<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Row;

use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Exception\EnterNewEntryException;

final readonly class OneToSixRowRule implements RowRuleInterface
{
    public function supports(ScoreEntryFieldEnum $field): bool
    {
        return in_array($field, [
            ScoreEntryFieldEnum::ONES,
            ScoreEntryFieldEnum::TWOS,
            ScoreEntryFieldEnum::THREES,
            ScoreEntryFieldEnum::FOURS,
            ScoreEntryFieldEnum::FIVES,
            ScoreEntryFieldEnum::SIXES,
        ], true);
    }

    public function calculate(ScoreEntryFieldEnum $field, array $dices): int
    {
        $targetValue = $this->targetValue($field);

        $matches = array_values(
            array_filter(
                $dices,
                static fn (int $dice): bool => $dice === $targetValue
            )
        );

        return array_sum(array_slice($matches, 0, 5));
    }

    private function targetValue(ScoreEntryFieldEnum $field): int
    {
        return match ($field) {
            ScoreEntryFieldEnum::ONES => 1,
            ScoreEntryFieldEnum::TWOS => 2,
            ScoreEntryFieldEnum::THREES => 3,
            ScoreEntryFieldEnum::FOURS => 4,
            ScoreEntryFieldEnum::FIVES => 5,
            ScoreEntryFieldEnum::SIXES => 6,
            default => throw EnterNewEntryException::notPossibleToEnterInThisField()
        };
    }
}
