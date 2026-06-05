import {
    LOWER_SECTION_ROWS,
    UPPER_SECTION_ROWS
} from "../../../config/GameRowsConfig.tsx";

export class GameBoardService {
    static recalculateTotals(data: any, column: string) {
        const grid = data.grid ?? {};

        const upperSum = this.calculateUpperSum(grid, column);
        const upperTotal = this.applyUpperBonus(upperSum);

        const downSum = this.calculateDownSum(grid, column);
        const minMaxTotal = this.calculateMinMax(grid, column);

        data.upperTotals ??= {};
        data.downTotals ??= {};
        data.minMaxTotals ??= {};
        data.grandTotals ??= {};

        data.upperTotals[column] ??= {};
        data.downTotals[column] ??= {};
        data.minMaxTotals[column] ??= {};

        data.upperTotals[column].upper_sum = upperTotal;
        data.downTotals[column].down_sum = downSum;
        data.minMaxTotals[column].min_max_total = minMaxTotal;

        data.grandTotals[column] = upperTotal + downSum + minMaxTotal;

        return data;
    }

    static calculateUpperSum(grid: any, column: string) {
        return UPPER_SECTION_ROWS.reduce(
            (sum, row) =>
                sum + (Number(grid?.[row]?.[column]) || 0),
            0
        );
    }

    static applyUpperBonus(sum: number) {
        return sum >= 60 ? sum + 30 : sum;
    }

    static calculateDownSum(grid: any, column: string) {
        return LOWER_SECTION_ROWS.reduce(
            (sum, row) =>
                sum + (Number(grid?.[row]?.[column]) || 0),
            0
        );
    }

    static calculateMinMax(grid: any, column: string) {
        const ones = grid?.ones?.[column];
        const max = grid?.max?.[column];
        const min = grid?.min?.[column];

        const isValid =
            ones !== undefined &&
            max !== undefined &&
            min !== undefined &&
            ones !== null &&
            max !== null &&
            min !== null &&
            ones !== 0 &&
            max !== 0 &&
            min !== 0;

        if (!isValid) return 0;

        return (Number(max) - Number(min)) * Number(ones);
    }

    static applyScore(gameData: any, row: string, column: string, points: number) {
        const updated = structuredClone(gameData);

        updated.grid[row][column] = points;
        updated.dices = [];
        updated.throwNumber = 0;

        this.recalculateTotals(updated, column);

        return updated;
    }
}
