import {LOWER_SECTION_ROWS, UPPER_SECTION_ROWS} from "../../../config/GameRowsConfig.tsx";

export class GameBoardService {
    static recalculateTotals(data: any, column: string) {
        const upperSum = UPPER_SECTION_ROWS.reduce(
            (sum, row) => sum + (Number(data.grid?.[row]?.[column]) || 0),
            0
        );

        const downSum = LOWER_SECTION_ROWS.reduce(
            (sum, row) => sum + (Number(data.grid?.[row]?.[column]) || 0),
            0
        );

        const minMaxTotal =
            Number(data.minMaxTotals?.[column]?.min_max_total) || 0;

        data.upperTotals ??= {};
        data.downTotals ??= {};
        data.grandTotals ??= {};

        data.upperTotals[column] ??= {};
        data.downTotals[column] ??= {};

        data.upperTotals[column].upper_sum = upperSum;
        data.downTotals[column].down_sum = downSum;
        data.grandTotals[column] =
            upperSum + downSum + minMaxTotal;

        return data;
    }

    static applyScore(
        gameData: any,
        row: string,
        column: string,
        points: number
    ) {
        const updated = structuredClone(gameData);

        updated.grid[row][column] = points;
        updated.dices = [];
        updated.throwNumber = 0;

        this.recalculateTotals(updated, column);

        return updated;
    }
}
