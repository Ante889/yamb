<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Row;

use App\Game\Domain\Enum\ScoreEntryFieldEnum;

final class StraightRowRule implements RowRuleInterface
{
    private const SMALL_STRAIGHT = [1, 2, 3, 4, 5];
    private const LARGE_STRAIGHT = [2, 3, 4, 5, 6];

    private const SMALL_SCORE = 45;
    private const LARGE_SCORE = 50;

    public function supports(ScoreEntryFieldEnum $field): bool
    {
        return $field === ScoreEntryFieldEnum::STRAIGHT;
    }

    public function calculate(ScoreEntryFieldEnum $field, array $dices): int
    {
        $sorted = $this->getUniqueSorted($dices);

        return match (true) {
            count(array_diff(self::LARGE_STRAIGHT, $sorted)) === 0 => self::LARGE_SCORE,
            count(array_diff(self::SMALL_STRAIGHT, $sorted)) === 0 => self::SMALL_SCORE,
            default => 0,
        };
    }

    private function getUniqueSorted(array $dices): array
    {
        $unique = array_unique($dices);
        sort($unique);

        return array_values($unique);
    }
}
