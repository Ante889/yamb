import styles from "./Dashboard.module.css";
import soloGameImage from "../../assets/dashboard/SoloGame.jpg";
import pvpImage from "../../assets/dashboard/Pvp.jpg";
import leaderboardImage from "../../assets/dashboard/Leaderboard.jpg";
import yambText from "../../assets/dashboard/Yamb.png";
import { useTranslation } from "react-i18next";
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import toast from "react-hot-toast";
import CreateGameModal from "../../components/CreateGameModal/CreateGameModal.tsx";
import { SoloGameService } from "../../services/game/soloGame/soloGameService.tsx";

function Dashboard() {
    const { t } = useTranslation();
    const navigate = useNavigate();
    const [isModalOpen, setIsModalOpen] = useState(false);

    const handleSoloStart = async ({ diceCount, gameType }) => {
        const loadingToast = toast.loading(t("loading"));

        try {
            const gameData = await SoloGameService.createSoloGame(diceCount, gameType);
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
            </div>

            <div className={styles.grid}>
                <div className={styles.gameCard} onClick={() => setIsModalOpen(true)}>
                    <img src={soloGameImage} alt="play-solo" />
                    <div className={styles.overlay}>
                        <h3>{t("dashboard.playSolo")}</h3>
                        <p>{t("dashboard.playSoloDesc")}</p>
                    </div>
                </div>

                <div className={styles.gameCard} onClick={() => navigate("/room-list")}>
                    <img src={pvpImage} alt="create-room" />
                    <div className={styles.overlay}>
                        <h3>{t("dashboard.duel")}</h3>
                        <p>{t("dashboard.createRoomDesc")}</p>
                    </div>
                </div>

                <div className={styles.leaderboardCard}>
                    <img src={leaderboardImage} alt="leaderboard" onClick={() => navigate("/leaderboard")} />
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
