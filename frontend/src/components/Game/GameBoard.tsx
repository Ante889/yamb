import {DiceRoller} from "./DiceRoller.tsx";
import ConfirmExitModal from "../Modal/ConfirmExitModal.tsx";
import {useState} from "react";
import {useTranslation} from "react-i18next";
import {SoloGameService} from "../../services/game/soloGame/soloGameService.tsx";
import {useNavigate} from "react-router-dom";

type GameColumnDefinition = {
    label: string;
    gridKey: string;
};

type GameBoardProps = {
    gameData: any;
    columns: GameColumnDefinition[];
    styles: Record<string, string>;
};

export function GameBoard({ gameData, columns, styles, participantId }: GameBoardProps) {
    const grid = gameData?.grid ?? {};
    const { t } = useTranslation();
    const [showExit, setShowExit] = useState(false);
    const navigate = useNavigate();
    const upperSectionRows = [
        "ones",
        "twos",
        "threes",
        "fours",
        "fives",
        "sixes",
    ];

    const minMaxRows = ["Min", "Max"];

    const lowerSectionRows = [
        "two_pairs",
        "Tris",
        "Straight",
        "Full",
        "Poker",
        "Yamb",
    ];

    const upperTotals = gameData?.upperTotals ?? {};
    const minMaxTotals = gameData?.minMaxTotals ?? {};
    const downTotals = gameData?.downTotals ?? {};
    const grandTotals = gameData?.grandTotals ?? {};

    const renderRow = (rowName: string) => (
        <div className={styles.row} key={rowName}>
            <div className={`${styles.cell} ${styles.cat}`}>
                {rowName}
            </div>

            {columns.map((columnDefinition) => (
                <div
                    key={columnDefinition.gridKey}
                    className={styles.cell}
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

        if(res) navigate("/");
    };

    return (
        <div className={styles.tableWrapper}>
            <div className={styles.sheet}>
                <DiceRoller
                    diceCount={gameData['diceNumber'] ?? 6}
                    dices={gameData['dices'] ?? []}
                    throwNumber={gameData['throwNumber'] ?? 0}
                />
                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}/>
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
                    <div className={`${styles.cell} ${styles.cat}`}>Sum</div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {upperTotals?.[columnDefinition.gridKey]?.upper_sum ?? 0}
                        </div>
                    ))}
                </div>

                {minMaxRows.map(renderRow)}

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>Sum</div>
                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {minMaxTotals?.[columnDefinition.gridKey]?.min_max_total ?? 0}
                        </div>
                    ))}
                </div>

                {lowerSectionRows.map(renderRow)}

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>Sum</div>
                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {downTotals?.[columnDefinition.gridKey]?.down_sum ?? 0}
                        </div>
                    ))}
                </div>

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>TOTAL</div>
                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.grandCell}`}
                        >
                            {grandTotals?.[columnDefinition.gridKey] ?? 0}
                        </div>
                    ))}
                </div>
                <button
                    className="btn btn-error"
                    onClick={() => setShowExit(true)}
                >
                    {t('game.exit')}
                </button>
            </div>
            <ConfirmExitModal
                open={showExit}
                onClose={() => setShowExit(false)}
                onConfirm={() => {
                    setShowExit(false);
                    exitGame()
                }}
            />
        </div>
    );
}
