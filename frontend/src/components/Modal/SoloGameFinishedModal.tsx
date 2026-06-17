import Modal from "./Modal.tsx";
import {useTranslation} from "react-i18next";
import {useNavigate} from "react-router-dom";

function SoloGameFinishedModal({
                                   isModalOpen,
                                   points
                               }) {
    const { t } = useTranslation();
    const navigate = useNavigate();

    return (
        <Modal
            isOpen={isModalOpen}
            title={t("game.gameIsFinished")}
            withExit={false}
        >
            <div style={{ textAlign: "center", fontSize: "18px", marginBottom: "12px" }}>
                <div style={{ opacity: 0.7, marginBottom: "6px" }}>
                    {t('game.pointsNumber')}
                </div>
                <div style={{ fontSize: "28px", fontWeight: "700" }}>
                    {points}
                </div>
            </div>

            <button
                style={{marginBottom: '10px'}}
                className="btn btn-primary fullSize"
                onClick={() => navigate('/')}
            >
                {t('dashboard.dashboard')}
            </button>

            <button
                className="btn fullSize"
                onClick={() => navigate('/leaderboard')}
            >
                {t('dashboard.leaderboard')}
            </button>
        </Modal>
    );
}

export default SoloGameFinishedModal;
