<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Application\Dto\ScoreSheetResult;
use App\Game\Domain\Enum\GameTypeEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Model\ScoreEntry;

final readonly class ScoreSheetCalculator
{
    private const KEY_MIN = 'min';
    private const KEY_MAX = 'max';
    private const KEY_ONES = 'ones';

    public function calculate(
        array        $entries,
        array        $dices,
        int          $diceNumber,
        int          $throwNumber,
        GameTypeEnum $gameType,
        ?string       $announcement
    ): ScoreSheetResult
    {

        $grid = [];
        $indexed = [];

        /** @var ScoreEntry $entry */
        foreach ($entries as $entry) {
            $indexed[$entry->getField()->value][$entry->getEntryColumn()->value] = $entry->getValue();
        }

        foreach (ScoreEntryFieldEnum::cases() as $field) {
            foreach ($gameType->columns() as $column) {
                $grid[$field->value][$column->value] =
                    $indexed[$field->value][$column->value] ?? null;
            }
        }

        $upperTotals = $this->calculateUpperTotals($grid, $gameType);
        $minMaxTotals = $this->calculateMinMaxTotals($grid, $gameType);
        $downTotals = $this->calculateDownTotals($grid, $gameType);

        $grandTotals = $this->calculateGrandTotals(
            $upperTotals,
            $minMaxTotals,
            $downTotals,
            $gameType
        );

        return new ScoreSheetResult(
            grid: $grid,
            upperTotals: $upperTotals,
            minMaxTotals: $minMaxTotals,
            downTotals: $downTotals,
            grandTotals: $grandTotals,
            diceNumber: $diceNumber,
            dices: $dices,
            throwNumber: $throwNumber,
            gameType: $gameType->value,
            announcement: $announcement
        );
    }

    private function calculateUpperTotals(array $grid, GameTypeEnum $gameType): array
    {
        $fields = [
            ScoreEntryFieldEnum::ONES->value,
            ScoreEntryFieldEnum::TWOS->value,
            ScoreEntryFieldEnum::THREES->value,
            ScoreEntryFieldEnum::FOURS->value,
            ScoreEntryFieldEnum::FIVES->value,
            ScoreEntryFieldEnum::SIXES->value,
        ];

        $totals = [];

        foreach ($gameType->columns() as $column) {

            $upperSum = 0;

            foreach ($fields as $field) {
                $upperSum += (int)($grid[$field][$column->value] ?? 0);
            }

            $bonus = ($upperSum >= 60) ? 30 : 0;

            $totals[$column->value] = [
                'upper_sum' => $upperSum + $bonus,
            ];
        }

        return $totals;
    }

    private function calculateMinMaxTotals(array $grid, GameTypeEnum $gameType): array
    {
        $totals = [];

        foreach ($gameType->columns() as $column) {

            $min = $grid[self::KEY_MIN][$column->value] ?? null;
            $max = $grid[self::KEY_MAX][$column->value] ?? null;
            $ones = $grid[self::KEY_ONES][$column->value] ?? null;

            $isValid =
                $min !== null &&
                $max !== null &&
                $ones !== null;

            if (!$isValid) {
                $totals[$column->value] = [
                    'min_max_total' => 0,
                ];
                continue;
            }

            $min = (int)$min;
            $max = (int)$max;
            $ones = (int)$ones;

            $totals[$column->value] = [
                'min_max_total' => ($max - $min) * $ones,
            ];
        }

        return $totals;
    }

    private function calculateDownTotals(array $grid, GameTypeEnum $gameType): array
    {
        $fields = [
            ScoreEntryFieldEnum::TWO_PAIRS->value,
            ScoreEntryFieldEnum::TRIS->value,
            ScoreEntryFieldEnum::STRAIGHT->value,
            ScoreEntryFieldEnum::FULL->value,
            ScoreEntryFieldEnum::POKER->value,
            ScoreEntryFieldEnum::YAMB->value,
        ];

        $totals = [];

        foreach ($gameType->columns() as $column) {

            $sum = 0;

            foreach ($fields as $field) {
                $sum += (int)($grid[$field][$column->value] ?? 0);
            }

            $totals[$column->value] = [
                'down_sum' => $sum,
            ];
        }

        return $totals;
    }

    private function calculateGrandTotals(
        array        $upper,
        array        $minMax,
        array        $down,
        GameTypeEnum $gameType
    ): array
    {

        $totals = [];

        foreach ($gameType->columns() as $column) {

            $totals[$column->value] =
                ($upper[$column->value]['upper_sum'] ?? 0)
                + ($minMax[$column->value]['min_max_total'] ?? 0)
                + ($down[$column->value]['down_sum'] ?? 0);
        }

        return $totals;
    }
}
