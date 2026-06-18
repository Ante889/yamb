import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import styles from "./PublicRooms.module.css";

function PublicRooms() {
    const { t } = useTranslation();
    const navigate = useNavigate();

    const rooms = [
        { id: "AB12", name: "Casual Lobby", players: 3 },
        { id: "XY99", name: "Ranked Arena", players: 5 },
        { id: "LM77", name: "Party Room", players: 2 },
        { id: "QW45", name: "Fun Zone", players: 6 },
    ];

    return (
        <div className={styles.publicRooms}>
            <div className={styles.header}>
                <button
                    className={'backButton'}
                    onClick={() => navigate(-1)}
                >
                    ← {t("dashboard.back")}
                </button>

                <h1>{t("publicRooms.title")}</h1>
            </div>

            <div className={styles.grid}>
                {rooms.map((room) => (
                    <div
                        key={room.id}
                        className={'card'}
                        onClick={() => navigate(`/room/${room.id}`)}
                        style={{cursor: 'pointer'}}
                    >
                        <div className={styles.roomContent}>
                            <span className={styles.icon}>🎲</span>

                            <h3>{room.name}</h3>

                            <p>
                                {room.players} {t("publicRooms.players")}
                            </p>

                            <span className={styles.code}>
                                {room.id}
                            </span>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}

export default PublicRooms;
