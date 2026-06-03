import { DiceRoller } from "./DiceRoller.tsx";
import ConfirmExitModal from "../Modal/ConfirmExitModal.tsx";
import { useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import { SoloGameService } from "../../services/game/soloGame/soloGameService.tsx";
import { useNavigate } from "react-router-dom";
import { ScoreEntryService } from "../../services/game/scoreEntry/scoreEntryService.tsx";

type GameColumnDefinition = {
    label: string;
    gridKey: string;
};

type GameBoardProps = {
    gameData: any;
    columns: GameColumnDefinition[];
    styles: Record<string, string>;
    participantId: number;
};

export function GameBoard({
                              gameData,
                              columns,
                              styles,
                              participantId,
                          }: GameBoardProps) {
    const { t } = useTranslation();
    const [showExit, setShowExit] = useState(false);
    const [localGameData, setLocalGameData] = useState(gameData);
    const navigate = useNavigate();

    useEffect(() => {
        setLocalGameData(gameData);
    }, [gameData]);

    const upperSectionRows = [
        "ones",
        "twos",
        "threes",
        "fours",
        "fives",
        "sixes",
    ];

    const minMaxRows = ["max", "min"];

    const lowerSectionRows = [
        "two_pairs",
        "tris",
        "straight",
        "full",
        "poker",
        "yamb",
    ];

    const grid = localGameData?.grid ?? {};
    const upperTotals = localGameData?.upperTotals ?? {};
    const minMaxTotals = localGameData?.minMaxTotals ?? {};
    const downTotals = localGameData?.downTotals ?? {};
    const grandTotals = localGameData?.grandTotals ?? {};

    const recalculateTotals = (data: any, column: string) => {
        const upperSum = upperSectionRows.reduce(
            (sum, row) => sum + (Number(data.grid?.[row]?.[column]) || 0),
            0
        );

        const downSum = lowerSectionRows.reduce(
            (sum, row) => sum + (Number(data.grid?.[row]?.[column]) || 0),
            0
        );

        const minMaxTotal =
            Number(data.minMaxTotals?.[column]?.min_max_total) || 0;

        if (!data.upperTotals[column]) {
            data.upperTotals[column] = {};
        }

        if (!data.downTotals[column]) {
            data.downTotals[column] = {};
        }

        data.upperTotals[column].upper_sum = upperSum;
        data.downTotals[column].down_sum = downSum;
        data.grandTotals[column] = upperSum + downSum + minMaxTotal;
    };

    const handleCellClick = async (
        row: string,
        column: string
    ) => {
        const response =
            await ScoreEntryService.newScoreEntryByParticipant(
                participantId,
                row,
                column
            );

        if (!response) return;

        setLocalGameData((prev: any) => {
            const updated = structuredClone(prev);

            updated.grid[row][column] = response.point;
            updated.dices = [];
            updated.throwNumber = 0;

            recalculateTotals(updated, column);

            return updated;
        });
    };

    const renderRow = (rowName: string) => (
        <div className={styles.row} key={rowName}>
            <div className={`${styles.cell} ${styles.cat}`}>
                {rowName}
            </div>

            {columns.map((columnDefinition) => (
                <div
                    key={columnDefinition.gridKey}
                    className={styles.cell}
                    onClick={() =>
                        handleCellClick(
                            rowName,
                            columnDefinition.gridKey
                        )
                    }
                >
                    {grid?.[rowName]?.[columnDefinition.gridKey] ?? "-"}
                </div>
            ))}
        </div>
    );

    const exitGame = async () => {
        const res = await SoloGameService.exitSoloGame(
            participantId
        );

        if (res) navigate("/");
    };

    return (
        <div className={styles.tableWrapper}>
            <div className={styles.sheet}>
                <DiceRoller
                    diceCount={localGameData?.diceNumber ?? 6}
                    dices={localGameData?.dices ?? []}
                    throwNumber={localGameData?.throwNumber ?? 0}
                />

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`} />

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.rule}`}
                        >
                            {columnDefinition.label}
                        </div>
                    ))}
                </div>

                {upperSectionRows.map(renderRow)}

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>
                        Sum
                    </div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {upperTotals?.[columnDefinition.gridKey]
                                ?.upper_sum ?? 0}
                        </div>
                    ))}
                </div>

                {minMaxRows.map(renderRow)}

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>
                        Sum
                    </div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {minMaxTotals?.[columnDefinition.gridKey]
                                ?.min_max_total ?? 0}
                        </div>
                    ))}
                </div>

                {lowerSectionRows.map(renderRow)}

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>
                        Sum
                    </div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {downTotals?.[columnDefinition.gridKey]
                                ?.down_sum ?? 0}
                        </div>
                    ))}
                </div>

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>
                        TOTAL
                    </div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.grandCell}`}
                        >
                            {grandTotals?.[
                                columnDefinition.gridKey
                                ] ?? 0}
                        </div>
                    ))}
                </div>

                <button
                    className="btn btn-error"
                    onClick={() => setShowExit(true)}
                >
                    {t("game.exit")}
                </button>
            </div>

            <ConfirmExitModal
                open={showExit}
                onClose={() => setShowExit(false)}
                onConfirm={() => {
                    setShowExit(false);
                    exitGame();
                }}
            />
        </div>
    );
}
