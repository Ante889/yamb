import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import toast from "react-hot-toast";
import styles from "./RoomList.module.css";
import CreateGameModal from "../../components/CreateGameModal/CreateGameModal.tsx";
import { RoomService } from "../../services/game/room/roomService.tsx";

function RoomList() {
    const { t } = useTranslation();
    const navigate = useNavigate();

    const [roomCode, setRoomCode] = useState("");
    const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);

    const handleJoin = async () => {
        if (!roomCode.trim()) return;

        const loadingToast = toast.loading(t("loading"));

        try {
            const room = await RoomService.joinRoomWithJoinCode(roomCode);
            navigate(`/room/${room.key}`);
        } finally {
            toast.dismiss(loadingToast);
        }
    };

    const handleRoomCreate = async ({ diceCount, gameType, isPublic }) => {
        const loadingToast = toast.loading(t("loading"));

        try {
            const room = await RoomService.createRoom(diceCount, gameType, isPublic);
            navigate("/room/" + room.key);
        } finally {
            toast.dismiss(loadingToast);
        }
    };

    return (
        <>
            <div className={styles.roomList}>
                <div className={styles.header}>
                    <button
                        className="backButton"
                        onClick={() => navigate('/')}
                    >
                        ← {t("dashboard.back")}
                    </button>

                    <h1>{t("roomList.title")}</h1>

                    <p>{t("roomList.subtitle")}</p>
                </div>

                <div className={styles.joinCard}>
                    <div className={styles.joinOverlay}>
                        <div>
                            <h2>{t("roomList.joinRoom")}</h2>
                            <p>{t("roomList.joinRoomDesc")}</p>
                        </div>

                        <div className={styles.joinForm}>
                            <input
                                type="text"
                                value={roomCode}
                                onChange={(e) => setRoomCode(e.target.value.toUpperCase())}
                                placeholder={t("roomList.roomCode")}
                            />

                            <button onClick={handleJoin}>
                                {t("roomList.join")}
                            </button>
                        </div>
                    </div>
                </div>

                <div className={styles.grid}>
                    <div
                        className={styles.actionCard}
                        onClick={() => setIsCreateModalOpen(true)}
                    >
                        <div className={styles.cardContent}>
                            <span className={styles.icon}>🎲</span>
                            <h3>{t("roomList.createRoom")}</h3>
                            <p>{t("roomList.createRoomDesc")}</p>
                        </div>
                    </div>

                    <div
                        className={styles.actionCard}
                        onClick={() => navigate("/public-rooms")}
                    >
                        <div className={styles.cardContent}>
                            <span className={styles.icon}>👥</span>
                            <h3>{t("roomList.publicRooms")}</h3>
                            <p>{t("roomList.publicRoomsDesc")}</p>
                        </div>
                    </div>
                </div>
            </div>

            <CreateGameModal
                isOpen={isCreateModalOpen}
                onClose={() => setIsCreateModalOpen(false)}
                title={t("roomList.createRoom")}
                submitLabel={t("roomList.createRoom")}
                showVisibility
                onSubmit={handleRoomCreate}
            />
        </>
    );
}

export default RoomList;
