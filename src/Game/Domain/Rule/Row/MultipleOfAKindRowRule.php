<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Row;

use App\Game\Domain\Enum\ScoreEntryFieldEnum;

final class MultipleOfAKindRowRule implements RowRuleInterface
{
    private const TRIS_REWARD = 20;
    private const POKER_REWARD = 50;
    private const YAMB_REWARD = 60;

    private const TRIS_COUNT = 3;
    private const POKER_COUNT = 4;
    private const YAMB_COUNT = 5;

    public function supports(ScoreEntryFieldEnum $field): bool
    {
        return in_array($field, [
            ScoreEntryFieldEnum::TRIS,
            ScoreEntryFieldEnum::POKER,
            ScoreEntryFieldEnum::YAMB,
        ], true);
    }

    public function calculate(ScoreEntryFieldEnum $field, array $dices): int
    {
        $counts = array_count_values($dices);

        arsort($counts);

        $required = $this->requiredCount($field);
        $reward = $this->reward($field);

        foreach ($counts as $value => $count) {
            if ($count >= $required) {
                return ($value * $required) + $reward;
            }
        }

        return 0;
    }

    private function requiredCount(ScoreEntryFieldEnum $field): int
    {
        return match ($field) {
            ScoreEntryFieldEnum::TRIS => self::TRIS_COUNT,
            ScoreEntryFieldEnum::POKER => self::POKER_COUNT,
            ScoreEntryFieldEnum::YAMB => self::YAMB_COUNT,
        };
    }

    private function reward(ScoreEntryFieldEnum $field): int
    {
        return match ($field) {
            ScoreEntryFieldEnum::TRIS => self::TRIS_REWARD,
            ScoreEntryFieldEnum::POKER => self::POKER_REWARD,
            ScoreEntryFieldEnum::YAMB => self::YAMB_REWARD,
        };
    }
}
