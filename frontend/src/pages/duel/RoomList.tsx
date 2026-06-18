import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import styles from "./RoomList.module.css";

function RoomList() {
    const { t } = useTranslation();
    const navigate = useNavigate();

    const [roomCode, setRoomCode] = useState("");

    const handleJoin = () => {
        if (!roomCode.trim()) return;

        navigate(`/room/${roomCode}`);
    };

    return (
        <div className={styles.roomList}>
            <div className={styles.header}>
                <button
                    className={'backButton'}
                    onClick={() => navigate(-1)}
                >
                    ← {t("dashboard.back")}
                </button>
                <h1>
                    {t("roomList.title")}
                </h1>

                <p>
                    {t("roomList.subtitle")}
                </p>
            </div>

            <div className={styles.joinCard}>
                <div className={styles.joinOverlay}>
                    <div>
                        <h2>{t("roomList.joinRoom")}</h2>

                        <p>
                            {t("roomList.joinRoomDesc")}
                        </p>
                    </div>

                    <div className={styles.joinForm}>
                        <input
                            type="text"
                            value={roomCode}
                            onChange={(e) =>
                                setRoomCode(
                                    e.target.value.toUpperCase()
                                )
                            }
                            placeholder={t("roomList.roomCode")}
                        />

                        <button onClick={handleJoin}>
                            {
                                t("roomList.join")
                            }
                        </button>
                    </div>
                </div>
            </div>

            <div className={styles.grid}>
                <div
                    className={styles.actionCard}
                    onClick={() => navigate("/create-room")}
                >
                    <div className={styles.cardContent}>
                        <span className={styles.icon}>🎲</span>

                        <h3>{t("roomList.createRoom")}</h3>

                        <p>
                            {t("roomList.createRoomDesc")}
                        </p>
                    </div>
                </div>

                <div
                    className={styles.actionCard}
                    onClick={() => navigate("/public-rooms")}
                >
                    <div className={styles.cardContent}>
                        <span className={styles.icon}>👥</span>

                        <h3>{t("roomList.publicRooms")}</h3>

                        <p>
                            {t("roomList.publicRoomsDesc")}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default RoomList;
