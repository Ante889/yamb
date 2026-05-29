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
     * @param iterable<ColumnRuleInterface> $rules
     */
    public function __construct(
        private iterable $rules,
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

        return 6;
    }

    private function validateColumn(ScoreEntryColumnEnum $column, ScoreEntryFieldEnum $row, int $participantId): void
    {
        foreach ($this->rules as $rule) {
            if ($rule->supports($column)) {
                if (!$rule->validate($row, $participantId)) {
                    throw EnterNewEntryException::notPossibleToEnterInThisField();
                }
            }
        }
    }
}
