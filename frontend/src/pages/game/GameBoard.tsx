import {DiceRoller} from "../../components/Game/DiceRoller.tsx";

type GameColumnDefinition = {
    label: string;
    gridKey: string;
};

type GameBoardProps = {
    gameData: any;
    columns: GameColumnDefinition[];
    styles: Record<string, string>;
};

export function GameBoard({ gameData, columns, styles }: GameBoardProps) {
    const grid = gameData?.grid ?? {};

    const upperSectionRows = [
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
    ];

    const minMaxRows = ["Min", "Max"];

    const lowerSectionRows = [
        "2 pairs",
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

    return (
        <div className={styles.tableWrapper}>
            <div className={styles.sheet}>
                <DiceRoller
                    diceCount={gameData['diceNumber'] ?? 6}
                    dices={gameData['dices'] ?? []}
                    throwNumber={gameData['throwNumber'] ?? 0}
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
            </div>
        </div>
    );
}
