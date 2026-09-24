import styles from "./Dashboard.module.css";
import soloGameImage from "../../assets/dashboard/SoloGame.jpg";
import pvpImage from "../../assets/dashboard/Pvp.jpg";
import leaderboardImage from "../../assets/dashboard/Leaderboard.jpg";
import yambText from "../../assets/dashboard/Yamb.png";
import { useTranslation } from "react-i18next";
import {useEffect, useState} from "react";
import { useNavigate } from "react-router-dom";
import toast from "react-hot-toast";
import CreateGameModal from "../../components/CreateGameModal/CreateGameModal.tsx";
import { SoloGameService } from "../../services/game/soloGame/soloGameService.tsx";
import {UserService} from "../../services/user/userService.tsx";
import {TokenService} from "../../api/token.tsx";

function Dashboard() {
    const { t } = useTranslation();
    const navigate = useNavigate();
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [loading, setLoading] = useState(false);
    const [playerStats, setPlayerStats] = useState([]);

    useEffect(() => {
        const loadStatistics = async () => {
            setLoading(true);

            try {
                const initial = await UserService.getStatistics(
                    TokenService.getUserId()
                );
                setPlayerStats(initial);
            } finally {
                setLoading(false);
            }
        };

        loadStatistics();
    }, []);

    const handleSoloStart = async ({ diceCount, gameType }) => {
        const loadingToast = toast.loading(t("loading"));

        try {
            const gameData = await SoloGameService.createSoloGame(
                diceCount,
                gameType
            );

            navigate("/solo-game/" + gameData.participants[0].id);
        } finally {
            toast.dismiss(loadingToast);
        }
    };

    return (
        <div className={styles.dashboard}>
            <div className={styles.header}>
                <img
                    className={styles.titleImage}
                    src={yambText}
                    alt="yamb-text"
                />
                <br/>
                <h3>{t("dashboard.duel")}</h3>
                <div className={styles.playerStats}>
                {loading || playerStats.length === 0 ? (
                        Array.from({ length: 2 }).map((_, index) => (
                            <div className={styles.statsLoading} key={index}>
                                <div className={styles.loader} />
                            </div>
                        ))
                ) :
                    (

                       <>
                           <div className={styles.stat}>
                                <span className={styles.statValue}>
                                    {playerStats.duel.totalGames}
                                </span>
                                       <span className={styles.statLabel}>
                                    {t("dashboard.games", "Games")}
                                </span>
                           </div>

                           <div className={styles.stat}>
                                <span className={styles.statValue}>
                                    {playerStats.duel.totalWins}
                                </span>
                                <span className={styles.statLabel}>
                                    {t("dashboard.wins", "Wins")}
                                </span>
                           </div>
                       </>

                    )}
            </div>
                <br/>

                <h3>{t("dashboard.records")}</h3>
                {loading || playerStats.length === 0 ? (
                    <div className={styles.statsLoading}>
                        <div className={styles.loader} />
                    </div>
                ) : (
                    <div className={styles.records}>
                        {playerStats.records.map((record, index) => (
                            <div
                                className={styles.record}
                                key={`${record.gameType}-${record.diceNumber}-${index}`}
                            >
                            <span className={styles.recordType}>
                                {t("game." + record.gameType)}
                            </span>

                                <span className={styles.recordDice}>
                                {record.diceNumber}{" "}
                                    {t("dashboard.dice", "dice")}
                            </span>

                                <span className={styles.recordPoints}>
                                {record.points}
                            </span>
                            </div>
                        ))}
                    </div>
                )}
            </div>

            <div className={styles.grid}>
                <div
                    className={styles.gameCard}
                    onClick={() => setIsModalOpen(true)}
                >
                    <img src={soloGameImage} alt="play-solo" />
                    <div className={styles.overlay}>
                        <h3>{t("dashboard.playSolo")}</h3>
                        <p>{t("dashboard.playSoloDesc")}</p>
                    </div>
                </div>

                <div
                    className={styles.gameCard}
                    onClick={() => navigate("/room-list")}
                >
                    <img src={pvpImage} alt="create-room" />
                    <div className={styles.overlay}>
                        <h3>{t("dashboard.duel")}</h3>
                        <p>{t("dashboard.createRoomDesc")}</p>
                    </div>
                </div>

                <div className={styles.leaderboardCard}>
                    <img
                        src={leaderboardImage}
                        alt="leaderboard"
                        onClick={() => navigate("/leaderboard")}
                    />
                    <div className={styles.overlay}>
                        <h3>{t("dashboard.leaderboard")}</h3>
                        <p>{t("dashboard.topPlayersThisWeek")}</p>
                    </div>
                </div>
            </div>

            <CreateGameModal
                isOpen={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                title={t("game.createSoloGame")}
                submitLabel={t("game.startGame")}
                onSubmit={handleSoloStart}
            />
        </div>
    );
}

export default Dashboard;