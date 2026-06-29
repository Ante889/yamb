import { useNavigate, useParams } from "react-router-dom";
import { useTranslation } from "react-i18next";
import styles from "./Room.module.css";
import {useEffect, useRef, useState} from "react";
import { GameService } from "../../../services/game/game/gameService.tsx";
import RoomLoading from "../../../components/Loading/RoomLoading.tsx";
import {MercureService} from "../../../services/mercure/mercureService.tsx";
import {applyEventForLobbyRoom} from "../../../mapper/roomMapper.tsx";
import {TokenService} from "../../../api/token.tsx";

function Room() {
    const { id } = useParams();
    const { t } = useTranslation();
    const [roomData, setRoomData] = useState(null);
    const [loading, setLoading] = useState(true);
    const esRef = useRef<EventSource | null>(null);

    useEffect(() => {
        (async () => {
            try {
                const res = await GameService.getGameById(id);
                setRoomData(res);
            } finally {
                setLoading(false);
            }
        })();
    }, [id]);

    useEffect(() => {
        let cancelled = false;

        const connect = async () => {
            const eventSource = await MercureService.subscribeToRooms((data) => {
                setRoomData((prev) =>
                    applyEventForLobbyRoom(
                        prev,
                        data.action,
                        data.payload
                    )
                );
            });

            if (!cancelled) {
                esRef.current = eventSource;
            } else {
                eventSource.close();
            }
        };

        connect();

        return () => {
            cancelled = true;
            esRef.current?.close();
        };
    }, []);

    const username = TokenService.getUsername();

    const currentUser = roomData?.participants?.find(
        (p) => p.username === username
    );

    const isHost = currentUser?.position === 1;

    const gameParticipantId = currentUser?.id;

    const participants = roomData?.participants ?? [];


    if (loading || !roomData) {
        return (
            <RoomLoading />
        );
    }

    return (
        <div className={styles.wrapper}>
            <div className={styles.container}>
                <div className={styles.header}>
                    <div>
                        <h1 className={styles.title}>
                            {t("room.gameLobby")}
                        </h1>
                        <p className={styles.subtitle}>
                            {t("room.waitingForPlayers")}
                        </p>
                    </div>

                    <div className={styles.codeBox}>
                        <span>{t("room.roomCode")}</span>
                        <strong>{roomData.joinCode}</strong>
                    </div>
                </div>

                <div className={styles.grid}>
                    <div className={styles.panel}>
                        <h2>
                            {t("room.players")} ({participants.length})
                        </h2>

                        <div className={styles.players}>
                            {participants.map((p) => (
                                <div key={p.id} className={styles.player}>
                                    <div className={styles.avatar} />

                                    <div className={styles.info}>
                                        <div className={styles.nameRow}>
                                            <span>{p.username}</span>

                                            {p.position === 1 && (
                                                <span className={styles.host}>
                                                    HOST
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    <div className={styles.panel}>
                        <h2>{t("room.gameSettings")}</h2>

                        <div className={styles.settings}>
                            <div className={styles.setting}>
                                <span>{t("room.dice")}</span>
                                <strong>{roomData.numberOfDice}</strong>
                            </div>

                            <div className={styles.setting}>
                                <span>{t("room.mode")}</span>
                                <strong>{t('game.' + roomData.type)}</strong>
                            </div>

                            <div className={styles.setting}>
                                <span>{t("room.visibility")}</span>
                                <strong>
                                    {roomData.isPublic ? "Public" : "Private"}
                                </strong>
                            </div>
                        </div>

                        {isHost && (
                            <button className="btn-primary fullSize">
                                {t("room.startGame")}
                            </button>
                        )}
                    </div>

                </div>
            </div>
        </div>
    );
}

export default Room;
