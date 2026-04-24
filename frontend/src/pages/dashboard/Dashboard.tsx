import { TokenService } from "../../api/token.tsx";
import "./Dashboard.css";
import yambImage from "../../assets/auth/YambLogin.jpg";
import { useTranslation } from "react-i18next";

function Dashboard() {
    const { t } = useTranslation();

    return (
        <div className="dashboard">
            <div className="dashboard-header">
                <h1 style={{fontSize: '40px'}} className="title">
                    {t("dashboard.welcome")} - {TokenService.getUsername()}
                </h1>
            </div>
            <div className="dashboard-grid">
                <div className="game-card">
                    <img src={yambImage} alt="play-solo"/>
                    <div className="overlay">
                        <h3>{t('dashboard.playSolo')}</h3>
                        <p>{t('dashboard.playSoloDesc')}</p>
                    </div>
                </div>

                <div className="game-card">
                    <img src={yambImage} alt="create-room"/>
                    <div className="overlay">
                        <h3>{t('dashboard.createRoom')}</h3>
                        <p>{t('dashboard.createRoomDesc')}</p>
                    </div>
                </div>
                <div className="leaderboard-card">
                    {/*<img src={yambImage} alt="create-room"/>*/}
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
