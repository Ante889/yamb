<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Row;

use App\Game\Domain\Enum\ScoreEntryFieldEnum;

final class TwoPairsRowRule implements RowRuleInterface
{
    private const REWARD = 10;

    public function supports(ScoreEntryFieldEnum $field): bool
    {
        return $field === ScoreEntryFieldEnum::TWO_PAIRS;
    }

    public function calculate(ScoreEntryFieldEnum $field, array $dices): int
    {
        $counts = array_count_values($dices);

        $pairs = [];

        foreach ($counts as $value => $count) {
            if ($count >= 2) {
                $pairs[] = $value * 2;
            }
        }

        if (count($pairs) < 2) {
            return 0;
        }

        rsort($pairs);

        $base = $pairs[0] + $pairs[1];

        return $base + self::REWARD;
    }
}
