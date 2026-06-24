import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import styles from "./PublicRooms.module.css";

function PublicRooms() {
    const { t } = useTranslation();
    const navigate = useNavigate();

    const [rooms, setRooms] = useState([]);

    useEffect(() => {
        setRooms([]);

        const url = new URL("http://localhost:3001/.well-known/mercure");
        url.searchParams.append("topic", "/rooms");
        url.searchParams.append("authorization", `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyIqIl19fQ.Ni9uIZUwNMhAHDpScXjD9JnwpSgqjXhkJdaUx7A4C6U`);

        const eventSource = new EventSource(url);

        eventSource.onopen = () => {
            console.log("✅ Mercure SPOJEN, readyState:", eventSource.readyState);
        };

        eventSource.onmessage = (event) => {
            console.log("📩 poruka:", event.data);
            const data = JSON.parse(event.data);
            const p = data.payload;

            setRooms((prev) => {
                switch (data.action) {

                    case "created":
                        return [...prev, {
                            id: p.key,
                            name: p.type,
                            players: p.participants?.length ?? 0
                        }];

                    case "updated":
                        return prev.map(room =>
                            room.id === p.key
                                ? {
                                    ...room,
                                    name: p.type ?? room.name,
                                    players: p.participants?.length ?? room.players
                                }
                                : room
                        );

                    case "removed":
                        return prev.filter(room => room.id !== p.key);

                    default:
                        return prev;
                }
            });
        };

        eventSource.onerror = () => {
            console.warn("⚠️ Mercure error, readyState:", eventSource.readyState);
        };

        return () => {
            eventSource.close();
        };
    }, []);

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
                        style={{ cursor: 'pointer' }}
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
