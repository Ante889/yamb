import "./Dashboard.css";
import soloGameImage from "../../assets/dashboard/SoloGame.jpg";
import pvpImage from "../../assets/dashboard/Pvp.jpg";
import leaderboardImage from "../../assets/dashboard/Leaderboard.jpg";
import yambText from "../../assets/dashboard/Yamb.png";
import { useTranslation } from "react-i18next";


function Dashboard() {
    const { t } = useTranslation();

    return (
        <div className="dashboard">
            <div className="dashboard-header">
                <img style={{width:'500px'}} src={yambText} alt="yamb-text"/>
            </div>
            <div className="dashboard-grid">
                <div className="game-card">
                    <img src={soloGameImage} alt="play-solo"/>
                    <div className="overlay">
                        <h3>{t('dashboard.playSolo')}</h3>
                        <p>{t('dashboard.playSoloDesc')}</p>
                    </div>
                </div>

                <div className="game-card">
                    <img src={pvpImage} alt="create-room"/>
                    <div className="overlay">
                        <h3>{t('dashboard.createRoom')}</h3>
                        <p>{t('dashboard.createRoomDesc')}</p>
                    </div>
                </div>
                <div className="leaderboard-card">
                    <img src={leaderboardImage} alt="create-room"/>
                    <div className="overlay">
                        <h3>{t('dashboard.leaderboard')}</h3>
                        <p>{t('dashboard.topPlayersThisWeek')}</p>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Dashboard;
