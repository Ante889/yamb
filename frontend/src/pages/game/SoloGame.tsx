import { useEffect, useState } from "react";
import styles from "./SoloGame.module.css";
import { ScoreEntryService } from "../../services/game/scoreEntry/scoreEntryService.tsx";
import {JambLoading} from "../../components/Loading/JambLoading.tsx";

function SoloGame() {
    const [data, setData] = useState<any>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchData = async () => {
            setLoading(true);
            try {
                const gameData = await ScoreEntryService.getScoreEntryByParticipant(3);
                setData(gameData);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    const rows = [
        "ones","twos","threes","fours","fives","sixes",
        "max","min","two_pairs","tris","straight","full","poker","yamb"
    ];

    if (loading) return <JambLoading/>;
    if (!data) return null;

    const g = data.grid;

    return (
        <div className={styles.sheetWrap}>
            <div className={styles.sheet}>

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}></div>

                    <div className={`${styles.cell} ${styles.rule}`} data-rule="Upisuje se od gore prema dolje">
                        ↓
                    </div>

                    <div className={`${styles.cell} ${styles.rule}`} data-rule="Upisuje se od dolje prema gore">
                        ↑
                    </div>

                    <div className={`${styles.cell} ${styles.rule}`} data-rule="Bilo koji redoslijed">
                        ↕
                    </div>

                    <div className={`${styles.cell} ${styles.rule}`} data-rule="Najava prije bacanja">
                        ANN
                    </div>
                </div>

                {rows.slice(0, 6).map((k) => (
                    <div className={styles.row} key={k}>
                        <div className={`${styles.cell} ${styles.cat}`}>{k}</div>
                        <div className={styles.cell}>{g?.[k]?.down ?? "-"}</div>
                        <div className={styles.cell}>{g?.[k]?.up ?? "-"}</div>
                        <div className={styles.cell}>{g?.[k]?.free ?? "-"}</div>
                        <div className={styles.cell}>{g?.[k]?.announcement ?? "-"}</div>
                    </div>
                ))}

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>SUM</div>
                    <div className={`${styles.cell} ${styles.totalCell}`}>
                        {data.upperTotals.down.upper_sum}
                    </div>
                    <div className={`${styles.cell} ${styles.totalCell}`}>
                        {data.upperTotals.up.upper_sum}
                    </div>
                    <div className={`${styles.cell} ${styles.totalCell}`}>
                        {data.upperTotals.free.upper_sum}
                    </div>
                    <div className={`${styles.cell} ${styles.totalCell}`}>
                        {data.upperTotals.announcement.upper_sum}
                    </div>
                </div>

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>MIN</div>
                    <div className={styles.cell}>{g?.min?.down ?? "-"}</div>
                    <div className={styles.cell}>{g?.min?.up ?? "-"}</div>
                    <div className={styles.cell}>{g?.min?.free ?? "-"}</div>
                    <div className={styles.cell}>{g?.min?.announcement ?? "-"}</div>
                </div>

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>MAX</div>
                    <div className={styles.cell}>{g?.max?.down ?? "-"}</div>
                    <div className={styles.cell}>{g?.max?.up ?? "-"}</div>
                    <div className={styles.cell}>{g?.max?.free ?? "-"}</div>
                    <div className={styles.cell}>{g?.max?.announcement ?? "-"}</div>
                </div>

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>SUM</div>
                    <div className={`${styles.cell} ${styles.totalCell}`}>
                        {data.minMaxTotals.down.min_max_total}
                    </div>
                    <div className={`${styles.cell} ${styles.totalCell}`}>
                        {data.minMaxTotals.up.min_max_total}
                    </div>
                    <div className={`${styles.cell} ${styles.totalCell}`}>
                        {data.minMaxTotals.free.min_max_total}
                    </div>
                    <div className={`${styles.cell} ${styles.totalCell}`}>
                        {data.minMaxTotals.announcement.min_max_total}
                    </div>
                </div>

                {rows
                    .filter((k) => !["min", "max"].includes(k))
                    .slice(6)
                    .map((k) => (
                        <div className={styles.row} key={k}>
                            <div className={`${styles.cell} ${styles.cat}`}>{k}</div>
                            <div className={styles.cell}>{g?.[k]?.down ?? "-"}</div>
                            <div className={styles.cell}>{g?.[k]?.up ?? "-"}</div>
                            <div className={styles.cell}>{g?.[k]?.free ?? "-"}</div>
                            <div className={styles.cell}>{g?.[k]?.announcement ?? "-"}</div>
                        </div>
                    ))}

                <div className={styles.row}>
                    <div className={`${styles.cell} ${styles.cat}`}>TOTAL</div>
                    <div className={`${styles.cell} ${styles.grandCell}`}>
                        {data.grandTotals.down}
                    </div>
                    <div className={`${styles.cell} ${styles.grandCell}`}>
                        {data.grandTotals.up}
                    </div>
                    <div className={`${styles.cell} ${styles.grandCell}`}>
                        {data.grandTotals.free}
                    </div>
                    <div className={`${styles.cell} ${styles.grandCell}`}>
                        {data.grandTotals.announcement}
                    </div>
                </div>

            </div>
        </div>
    );
}

export default SoloGame;
