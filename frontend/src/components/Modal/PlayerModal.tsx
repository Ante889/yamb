import Modal from "./Modal.tsx";
import { useTranslation } from "react-i18next";
import { useEffect, useState } from "react";
import { UserService } from "../../services/user/userService.tsx";
import styles from "./PlayerModal.module.css";

function PlayerModal({
                         isModalOpen,
                         onClose,
                         activePlayer
                     }) {
    const { t } = useTranslation();
    const [playerStats, setPlayerStats] = useState(null);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (!isModalOpen) {
            return;
        }

        const loadStatistics = async () => {
            setLoading(true);

            try {
                const statistics = await UserService.getStatistics(
                    activePlayer.userId
                );

                setPlayerStats(statistics);
            } finally {
                setLoading(false);
            }
        };

        loadStatistics();
    }, [isModalOpen]);

    const totalGames = playerStats?.duel?.totalGames ?? 0;
    const totalWins = playerStats?.duel?.totalWins ?? 0;

    const winRate = totalGames > 0
        ? Math.round((totalWins / totalGames) * 100)
        : 0;

    return (
        <Modal
            isOpen={isModalOpen}
            title={t("game.playerStatistics", "Player statistics")}
            withExit={true}
            onClose={onClose}
        >
            <div className={styles.container}>
                {loading ? (
                    <div className={styles.loading}>
                        <div className={styles.loader} />
                    </div>
                ) : playerStats ? (
                    <>
                        <h2 style={{textAlign:"center"}}>{playerStats.username}</h2>
                        <div className={styles.stats}>
                            <div className={styles.stat}>
                                <span className={styles.statValue}>
                                    {totalGames}
                                </span>
                                <span className={styles.statLabel}>
                                    {t("dashboard.games", "Games")}
                                </span>
                            </div>

                            <div className={styles.stat}>
                                <span className={styles.statValue}>
                                    {totalWins}
                                </span>
                                <span className={styles.statLabel}>
                                    {t("dashboard.wins", "Wins")}
                                </span>
                            </div>
                        </div>
                        <div className={styles.stat} style={{width:'100%'}}>
                                <span className={styles.statValue}>
                                    {winRate}%
                                </span>
                            <span className={styles.statLabel}>
                                    {t("dashboard.winRate", "Win rate")}
                                </span>
                        </div>

                        <div className={styles.recordsTitle}>
                            {t("dashboard.records", "Records")}
                        </div>

                        <div className={styles.records}>
                            {playerStats.records?.length > 0 ? (
                                playerStats.records.map((record, index) => (
                                    <div
                                        className={styles.record}
                                        key={`${record.gameType}-${record.diceNumber}-${index}`}
                                    >
                                        <span className={styles.recordType}>
                                            {t(`game.${record.gameType}`)}
                                        </span>

                                        <span className={styles.recordDice}>
                                            {record.diceNumber}{" "}
                                            {t("dashboard.dice", "dice")}
                                        </span>

                                        <span className={styles.recordPoints}>
                                            {record.points}
                                        </span>
                                    </div>
                                ))
                            ) : (
                                <div className={styles.noRecords}>
                                    {t(
                                        "dashboard.noRecords",
                                        "No records yet"
                                    )}
                                </div>
                            )}
                        </div>
                    </>
                ) : null}
            </div>
        </Modal>
    );
}

export default PlayerModal;