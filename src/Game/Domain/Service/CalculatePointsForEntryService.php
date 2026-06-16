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
     * @param array<int, ScoreEntry> $scoreEntryArray
     * @return void
     */
    public function getTotal(array $scoreEntries): int
    {
        //OVO MORAM DOVRŠITI -> Kad računa total onda treba biti max - min * 1
        return array_reduce(
            $scoreEntries,
            fn (int $total, $entry) =>
                $total + $entry->getField()->calculate($entry),
            0
        );
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
