<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Row;

use App\Game\Domain\Enum\ScoreEntryFieldEnum;

final class FullRowRule implements RowRuleInterface
{
    private const REWARD = 40;

    public function supports(ScoreEntryFieldEnum $field): bool
    {
        return $field === ScoreEntryFieldEnum::FULL;
    }

    public function calculate(ScoreEntryFieldEnum $field, array $dices): int
    {
        $counts = array_count_values($dices);

        $three = $this->findBest($counts, 3);
        if ($three === null) {
            return 0;
        }

        $pair = $this->findBest($counts, 2, $three);
        if ($pair === null) {
            return 0;
        }

        return ($three * 3) + ($pair * 2) + self::REWARD;
    }

    private function findBest(array $counts, int $minCount, ?int $exclude = null): ?int
    {
        $best = null;

        foreach ($counts as $value => $count) {
            if ($count >= $minCount && $value !== $exclude) {
                if ($best === null || $value > $best) {
                    $best = $value;
                }
            }
        }

        return $best;
    }
}
