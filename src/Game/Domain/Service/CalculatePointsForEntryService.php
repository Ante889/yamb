<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Exception\EnterNewEntryException;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;
use App\Game\Domain\Model\ScoreEntry;
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
        GameParticipant      $gameParticipant
    ): int
    {
        if($gameParticipant->getGame()->getGameStatus() !== GameStatusEnum::IN_PROGRESS->value) {
            throw EnterNewEntryException::notPossibleToEnterInThisField();
        }

        $this->validateColumn($column, $row, $gameParticipant);

        return $this->calculatePoints($row, $dices);
    }

    /**
     * @param array<int, ScoreEntry> $scoreEntries
     * @return int
     */
    public function calculateTotalScore(array $scoreEntries): int
    {
        $columns = [];

        foreach ($scoreEntries as $entry) {
            $column = $entry->getEntryColumn()->value;
            $field = $entry->getField()->value;

            $columns[$column][$field] = $entry->getValue();
        }

        $total = 0;
        $ignoredFields = ['min', 'max'];

        foreach ($columns as $entries) {
            $upper = array_sum(array_intersect_key($entries, array_flip(ScoreEntryFieldEnum::upper())));

            $columnTotal = array_sum(array_diff_key($entries, array_flip($ignoredFields)));

            if ($upper > 60) {
                $columnTotal += 30;
            }

            $columnTotal += (
                    ($entries[ScoreEntryFieldEnum::MAX->value] ?? 0)
                    - ($entries[ScoreEntryFieldEnum::MIN->value] ?? 0)
                ) * ($entries[ScoreEntryFieldEnum::ONES->value] ?? 0);

            $total += $columnTotal;
        }

        return $total;
    }

    private function validateColumn(ScoreEntryColumnEnum $column, ScoreEntryFieldEnum $row, GameParticipant $gameParticipant): void
    {
        foreach ($this->columnRules as $rule) {
            if ($rule->supports($column)) {
                if (!$rule->validate($column, $row, $gameParticipant->getId(), $gameParticipant->getGame())) {
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
