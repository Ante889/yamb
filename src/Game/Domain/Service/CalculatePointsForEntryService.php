<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Exception\EnterNewEntryException;
use App\Game\Domain\Rule\Column\ColumnRuleInterface;

final readonly class CalculatePointsForEntryService
{
    /**
     * @param iterable<ColumnRuleInterface> $columnRules
     */
    public function __construct(
        private iterable $columnRules,
        private iterable $rowRules
    )
    {
    }

    public function calculate(
        array                $dices,
        ScoreEntryColumnEnum $column,
        ScoreEntryFieldEnum  $row,
        int                  $participantId
    ): int
    {
        $this->validateColumn($column, $row, $participantId);

        return $this->calculatePoints($row, $dices);
    }

    private function validateColumn(ScoreEntryColumnEnum $column, ScoreEntryFieldEnum $row, int $participantId): void
    {
        foreach ($this->columnRules as $rule) {
            if ($rule->supports($column)) {
                if (!$rule->validate($row, $participantId)) {
                    throw EnterNewEntryException::notPossibleToEnterInThisField();
                }
            }
        }
    }

    private function calculatePoints(ScoreEntryFieldEnum $row, array $dices): int
    {
        $points = 0;

        foreach ($this->rowRules as $rule) {
            if ($rule->supports($row)) {
                $points = $rule->calculate($row, $dices);
            }
        }

        return $points;
    }
}
