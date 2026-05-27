import { useEffect, useState } from "react";
import styles from "./SoloGame.module.css";
import { ScoreEntryService } from "../../services/game/scoreEntry/scoreEntryService.tsx";
import {JambLoading} from "../../components/Loading/JambLoading.tsx";
import {useNavigate, useParams} from "react-router-dom";
import {DiceRoller} from "../../components/Game/DiceRoller.tsx";
import {useTranslation} from "react-i18next";
import ConfirmExitModal from "../../components/Modal/ConfirmExitModal.tsx";
import {SoloGameService} from "../../services/game/soloGame/soloGameService.tsx";

function SoloGame() {
    const [data, setData] = useState<never>(null);
    const [loading, setLoading] = useState(true);
    const { t } = useTranslation();
    const [showExit, setShowExit] = useState(false);
    const navigate = useNavigate();
    const { id } = useParams();

    useEffect(() => {
        const fetchData = async () => {
            if (!id) return;

            setLoading(true);
            try {
                const gameData = await ScoreEntryService.getScoreEntryByParticipant(Number(id));
                setData(gameData);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, [id]);

    const exitGame = async () => {
        const res = await SoloGameService.exitSoloGame(
            id
        );

        if(res) navigate("/");
    };

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
                <DiceRoller
                    diceCount={data['diceNumber'] ?? 6}
                    dices={data['dices'] ?? []}
                    throwNumber={data['throwNumber'] ?? 0}
                />
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
                <button
                    className="btn btn-error"
                    onClick={() => setShowExit(true)}
                >
                    {t('game.exit')}
                </button>
                <ConfirmExitModal
                    open={showExit}
                    onClose={() => setShowExit(false)}
                    onConfirm={() => {
                        setShowExit(false);
                        exitGame()
                    }}
                />
            </div>
        </div>
    );
}

export default SoloGame;
