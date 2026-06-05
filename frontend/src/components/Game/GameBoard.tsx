import { DiceRoller } from "./DiceRoller.tsx";
import ConfirmExitModal from "../Modal/ConfirmExitModal.tsx";
import { useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import { SoloGameService } from "../../services/game/soloGame/soloGameService.tsx";
import { ScoreEntryService } from "../../services/game/scoreEntry/scoreEntryService.tsx";
import { GameBoardService } from "../../services/game/gameBoard/gameBoardService.tsx";
import { useNavigate } from "react-router-dom";
import {LOWER_SECTION_ROWS, MIN_MAX_ROWS, UPPER_SECTION_ROWS} from "../../config/GameRowsConfig.tsx";

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
    const navigate = useNavigate();

    const [showExit, setShowExit] = useState(false);
    const [localGameData, setLocalGameData] = useState(gameData);

    useEffect(() => {
        setLocalGameData(gameData);
    }, [gameData]);

    const grid = localGameData?.grid ?? {};
    const upperTotals = localGameData?.upperTotals ?? {};
    const minMaxTotals = localGameData?.minMaxTotals ?? {};
    const downTotals = localGameData?.downTotals ?? {};
    const grandTotals = localGameData?.grandTotals ?? {};
    const [announcedColumn, setAnnouncedColumn] = useState(localGameData?.announcement);
    const [throwCount, setThrowCount] = useState(localGameData?.throwNumber ?? 0);

    const handleCellClick = async (
        row: string,
        column: string
    ) => {

        if (column === 'announcement' && row !== announcedColumn) {
            await handleAnnouncementClick(row);
            return;
        }

        const response =
            await ScoreEntryService.newScoreEntryByParticipant(
                participantId,
                row,
                column
            );

        if (!response) return;

        setLocalGameData((prev: any) =>
            GameBoardService.applyScore(
                prev,
                row,
                column,
                response.point
            )
        );
        setAnnouncedColumn(null)
        setThrowCount(0)
    };

    const handleAnnouncementClick = async (
        row: string
    ) => {
        const response =
            await ScoreEntryService.setAnnouncement(
                participantId,
                row
            );

        if (!response) return;

        setAnnouncedColumn(response.row)
    };

    const exitGame = async () => {
        const res =
            await SoloGameService.exitSoloGame(participantId);

        if (res) {
            navigate("/");
        }
    };

    const renderRow = (rowName: string) => (
        <div
            className={styles.row}
            key={rowName}
        >
            <div className={`${styles.cell} ${styles.cat}`}>
                {rowName}
            </div>

            {columns.map((columnDefinition) => (
                <div
                    key={columnDefinition.gridKey}
                    className={`
                        ${styles.cell}
                            ${
                                announcedColumn === rowName && columnDefinition.gridKey === 'announcement'
                                    ? styles.announcedCell
                                    : ""
                            }
                        `}
                    onClick={() =>
                        handleCellClick(
                            rowName,
                            columnDefinition.gridKey
                        )
                    }
                >
                    {
                        grid?.[rowName]?.[
                            columnDefinition.gridKey
                            ] ?? "-"
                    }
                </div>
            ))}
        </div>
    );

    return (
        <div className={styles.tableWrapper}>
            <div className={styles.sheet}>
                <DiceRoller
                    diceCount={localGameData?.diceNumber ?? 6}
                    dices={localGameData?.dices ?? []}
                    throwCount={throwCount}
                    setThrowCount={setThrowCount}
                />

                <div className={styles.row}>
                    <div
                        className={`${styles.cell} ${styles.cat}`}
                    />

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`
                                ${styles.cell}
                                ${styles.rule}
                            `}
                        >
                            {columnDefinition.label}
                        </div>
                    ))}
                </div>

                {UPPER_SECTION_ROWS.map(renderRow)}

                <div className={styles.row}>
                    <div
                        className={`${styles.cell} ${styles.cat}`}
                    >
                        Sum
                    </div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {
                                upperTotals?.[
                                    columnDefinition.gridKey
                                    ]?.upper_sum ?? 0
                            }
                        </div>
                    ))}
                </div>

                {MIN_MAX_ROWS.map(renderRow)}

                <div className={styles.row}>
                    <div
                        className={`${styles.cell} ${styles.cat}`}
                    >
                        Sum
                    </div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {
                                minMaxTotals?.[
                                    columnDefinition.gridKey
                                    ]?.min_max_total ?? 0
                            }
                        </div>
                    ))}
                </div>

                {LOWER_SECTION_ROWS.map(renderRow)}

                <div className={styles.row}>
                    <div
                        className={`${styles.cell} ${styles.cat}`}
                    >
                        Sum
                    </div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {
                                downTotals?.[
                                    columnDefinition.gridKey
                                    ]?.down_sum ?? 0
                            }
                        </div>
                    ))}
                </div>

                <div className={styles.row}>
                    <div
                        className={`${styles.cell} ${styles.cat}`}
                    >
                        TOTAL
                    </div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.grandCell}`}
                        >
                            {
                                grandTotals?.[
                                    columnDefinition.gridKey
                                    ] ?? 0
                            }
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
