import styles from "./Dashboard.module.css";
import soloGameImage from "../../assets/dashboard/SoloGame.jpg";
import pvpImage from "../../assets/dashboard/Pvp.jpg";
import leaderboardImage from "../../assets/dashboard/Leaderboard.jpg";
import yambText from "../../assets/dashboard/Yamb.png";
import { useTranslation } from "react-i18next";
import {useState} from "react";
import CreateSoloGame from "./createGame/CreateSoloGame.tsx";

function Dashboard() {
    const { t } = useTranslation();
    const [isModalOpen, setIsModalOpen] = useState(false);

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

                <div className={styles.gameCard}>
                    <img src={pvpImage} alt="create-room" />
                    <div className={styles.overlay}>
                        <h3>{t("dashboard.createRoom")}</h3>
                        <p>{t("dashboard.createRoomDesc")}</p>
                    </div>
                </div>

                <div className={styles.leaderboardCard}>
                    <img src={leaderboardImage} alt="leaderboard" />
                    <div className={styles.overlay}>
                        <h3>{t("dashboard.leaderboard")}</h3>
                        <p>{t("dashboard.topPlayersThisWeek")}</p>
                    </div>
                </div>
            </div>
            <CreateSoloGame isModalOpen={isModalOpen} setIsModalOpen={setIsModalOpen}/>
        </div>
    );
}

export default Dashboard;
