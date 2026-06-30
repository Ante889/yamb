import { useNavigate, useParams } from "react-router-dom";
import { useTranslation } from "react-i18next";
import styles from "./Room.module.css";
import { useEffect, useRef, useState, useMemo } from "react";
import { GameService } from "../../../services/game/game/gameService.tsx";
import RoomLoading from "../../../components/Loading/RoomLoading.tsx";
import { MercureService } from "../../../services/mercure/mercureService.tsx";
import { applyEventForLobbyRoom } from "../../../mapper/roomMapper.tsx";
import { TokenService } from "../../../api/token.tsx";
import { RoomService } from "../../../services/game/room/roomService.tsx";

function Room() {
    const { id } = useParams();
    const { t } = useTranslation();
    const navigate = useNavigate();

    const [roomData, setRoomData] = useState<any>(null);
    const [loading, setLoading] = useState(true);

    const esRef = useRef<EventSource | null>(null);

    const username = TokenService.getUsername();

    const currentUser = useMemo(() => {
        return roomData?.participants?.find(
            (p: any) => p.username === username
        );
    }, [roomData, username]);

    const isHost = currentUser?.position === 1;
    const gameParticipantId = currentUser?.id;

    const participants = roomData?.participants ?? [];

    useEffect(() => {
        let cancelled = false;

        const load = async () => {
            try {
                const res = await GameService.getGameById(id);
                if (cancelled) return;
                setRoomData(res);
            } catch (e) {
                navigate("/room-list");
            } finally {
                if (!cancelled) setLoading(false);
            }
        };

        load();

        return () => {
            cancelled = true;
        };
    }, [id, navigate]);

    useEffect(() => {
        let cancelled = false;

        const connect = async () => {
            const eventSource = await MercureService.subscribeToRooms((data) => {
                setRoomData((prev) => {
                    const updated = applyEventForLobbyRoom(
                        prev,
                        data.action,
                        data.payload
                    );

                    if (!updated) {
                        navigate("/public-rooms");
                        return null;
                    }

                    return updated;
                });
            });

            if (cancelled) {
                eventSource.close();
                return;
            }

            esRef.current = eventSource;
        };

        connect();

        return () => {
            cancelled = true;
            esRef.current?.close();
        };
    }, [navigate]);


    useEffect(() => {
        if (!gameParticipantId) return;

        const sendHeartbeat = async () => {
            try {
                await RoomService.lobbyHeartBeat(gameParticipantId);
            } catch (err) {
                console.error("Heartbeat failed:", err);
            }
        };

        sendHeartbeat();

        const interval = setInterval(sendHeartbeat, 10000);

        return () => clearInterval(interval);
    }, [gameParticipantId]);


    if (loading || !roomData) {
        return <RoomLoading />;
    }

    return (
        <div className={styles.wrapper}>
            <div className={styles.container}>
                <button
                    className={"mainBackButton"}
                    onClick={() => navigate(-1)}
                >
                    ← {t("dashboard.back")}
                </button>

                <br />
                <br />

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
                            {participants.map((p: any) => (
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
                                <strong>{t("game." + roomData.type)}</strong>
                            </div>

                            <div className={styles.setting}>
                                <span>{t("room.visibility")}</span>
                                <strong>
                                    {roomData.isPublic
                                        ? "Public"
                                        : "Private"}
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
