<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Application\Dto\ScoreSheetResult;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Model\ScoreEntry;

final readonly class ScoreSheetCalculator
{
    private const KEY_MIN = 'min';
    private const KEY_MAX = 'max';
    private const KEY_ONES = 'ones';

    private const KEY_GRID = 'grid';
    private const KEY_UPPER = 'upper_totals';
    private const KEY_MIN_MAX = 'min_max_totals';
    private const KEY_DOWN = 'down_totals';
    private const KEY_GRAND = 'grand_totals';


    public function calculate(array $entries): ScoreSheetResult
    {
        $grid = [];
        $indexed = [];

        /** @var ScoreEntry $entry */
        foreach ($entries as $entry) {
            $indexed[$entry->getField()->value][$entry->getColumn()->value] = $entry->getValue();
        }

        foreach (ScoreEntryFieldEnum::cases() as $field) {
            foreach (ScoreEntryColumnEnum::cases() as $column) {
                $grid[$field->value][$column->value] =
                    $indexed[$field->value][$column->value] ?? null;
            }
        }

        $upperTotals = $this->calculateUpperTotals($grid);
        $minMaxTotals = $this->calculateMinMaxTotals($grid);
        $downTotals = $this->calculateDownTotals($grid);

        $grandTotals = $this->calculateGrandTotals(
            $upperTotals,
            $minMaxTotals,
            $downTotals
        );

        return new ScoreSheetResult(
            grid: $grid,
            upperTotals: $upperTotals,
            minMaxTotals: $minMaxTotals,
            downTotals: $downTotals,
            grandTotals: $grandTotals,
        );
    }

    private function calculateUpperTotals(array $grid): array
    {
        $fields = [
            ScoreEntryFieldEnum::ONES->value,
            ScoreEntryFieldEnum::TWOS->value,
            ScoreEntryFieldEnum::THREES->value,
            ScoreEntryFieldEnum::FOURS->value,
            ScoreEntryFieldEnum::FIVES->value,
            ScoreEntryFieldEnum::SIXES->value
        ];

        $totals = [];

        foreach (ScoreEntryColumnEnum::cases() as $column) {

            $upperSum = 0;

            foreach ($fields as $field) {
                $upperSum += (int) ($grid[$field][$column->value] ?? 0);
            }

            $bonus = ($upperSum >= 60) ? 30 : 0;

            $totals[$column->value] = [
                'upper_sum' => $upperSum + $bonus,
            ];
        }

        return $totals;
    }

    private function calculateMinMaxTotals(array $grid): array
    {
        $totals = [];

        foreach (ScoreEntryColumnEnum::cases() as $column) {

            $min = (int) ($grid[self::KEY_MIN][$column->value] ?? 0);
            $max = (int) ($grid[self::KEY_MAX][$column->value] ?? 0);
            $ones = (int) ($grid[self::KEY_ONES][$column->value] ?? 0);

            $totals[$column->value] = [
                'min_max_total' => ($max - $min) * $ones,
            ];
        }

        return $totals;
    }

    private function calculateDownTotals(array $grid): array
    {
        $fields = [
            ScoreEntryFieldEnum::TWO_PAIRS->value,
            ScoreEntryFieldEnum::TRIS->value,
            ScoreEntryFieldEnum::STRAIGHT->value,
            ScoreEntryFieldEnum::FULL->value,
            ScoreEntryFieldEnum::POKER->value,
            ScoreEntryFieldEnum::YAMB->value
        ];

        $totals = [];

        foreach (ScoreEntryColumnEnum::cases() as $column) {

            $sum = 0;

            foreach ($fields as $field) {
                $sum += (int) ($grid[$field][$column->value] ?? 0);
            }

            $totals[$column->value] = [
                'down_sum' => $sum,
            ];
        }

        return $totals;
    }

    private function calculateGrandTotals(
        array $upper,
        array $minMax,
        array $down
    ): array {
        $totals = [];

        foreach (ScoreEntryColumnEnum::cases() as $column) {

            $totals[$column->value] =
                ($upper[$column->value]['upper_sum'] ?? 0)
                + ($minMax[$column->value]['min_max_total'] ?? 0)
                + ($down[$column->value]['down_sum'] ?? 0);
        }

        return $totals;
    }
}
