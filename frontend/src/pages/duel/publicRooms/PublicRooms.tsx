import { useEffect, useMemo, useRef, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import styles from "./PublicRooms.module.css";
import {APP_PAGINATION} from "../../../config/AppConfig.tsx";
import { MercureService } from "../../../services/mercure/mercureService.tsx";
import { RoomService } from "../../../services/game/room/roomService.tsx";
import Pagination from "../../../components/Pagination/Pagination.tsx";
import {applyRoomEvent, mapMercureRoom, mapRestRoom} from "../../../mapper/roomMapper.tsx";
import toast from "react-hot-toast";
import PublicRoomsLoadingPage from "../../../components/Loading/PublicRoomsLoadingPage.tsx";

type Room = {
    id: number;
    name: string;
    players: string[];
};

const PAGE_SIZE = APP_PAGINATION;

function PublicRooms() {
    const { t } = useTranslation();
    const navigate = useNavigate();

    const [rooms, setRooms] = useState<Room[]>([]);
    const [page, setPage] = useState(1);
    const [loading, setLoading] = useState(true);
    const esRef = useRef<EventSource | null>(null);

    useEffect(() => {
        let cancelled = false;

        (async () => {
            setLoading(true)
            const initial = await RoomService.getPublicRooms();
            if (cancelled) return;

            const list = Array.isArray(initial) ? initial : [];
            setRooms(list.map(mapRestRoom));
            setLoading(false)
        })();

        return () => {
            cancelled = true;
        };
    }, []);

    useEffect(() => {
        let cancelled = false;

        const connect = async () => {
            const eventSource = await MercureService.subscribeToRooms((data) => {
                const incoming = mapMercureRoom(data.payload);

                setRooms((prev) =>
                    applyRoomEvent(
                        prev,
                        data.action,
                        incoming
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

    const totalPages = Math.max(1, Math.ceil(rooms.length / PAGE_SIZE));

    useEffect(() => {
        if (page > totalPages) {
            setPage(totalPages);
        }
    }, [page, totalPages]);

    const visibleRooms = useMemo(() => {
        const start = (page - 1) * PAGE_SIZE;
        return rooms.slice(start, start + PAGE_SIZE);
    }, [rooms, page]);

    const handleJoin = async (gameId) => {
        const loadingToast = toast.loading(t("loading"));

        try {
            const room = await RoomService.joinRoomWithGameId(gameId);
            navigate(`/room/${room.key}`);
        } finally {
            toast.dismiss(loadingToast);
        }
    };

    return (
        <div className={styles.publicRooms}>
            <div className={styles.header}>
                <button className={"backButton"} onClick={() => navigate("/room-list")}>
                    ← {t("dashboard.back")}
                </button>
                <h1>{t("publicRooms.title")}</h1>
            </div>

            {loading ? (
                <PublicRoomsLoadingPage />
            ) : (
                <>
                    {visibleRooms.length > 0 ? (
                        <>
                            <ul className={styles.list}>
                                {visibleRooms.map((room) => (
                                    <li
                                        key={room.id}
                                        className={styles.row}
                                        onClick={() => handleJoin(room.id)}
                                    >
                                        <span className={styles.icon}>🎲</span>

                                        <div className={styles.rowMain}>
                                            <h3 className={styles.rowName}>{t("game." + room.name)}</h3>
                                            <span className={styles.code}>#{room.id}</span>
                                        </div>

                                        <div className={styles.players}>
                                            {room.players.length === 0 ? (
                                                <span className={styles.empty}>{t("publicRooms.empty")}</span>
                                            ) : (
                                                room.players.map((player, i) => (
                                                    <span key={i} className={styles.player}>
                                        {player}
                                    </span>
                                                ))
                                            )}
                                        </div>
                                    </li>
                                ))}
                            </ul>

                            <Pagination page={page} totalPages={totalPages} onPageChange={setPage}/>
                        </>
                    ) : (
                        <div className={styles.emptyState}>
                            <p>{t("publicRooms.noRooms")}</p>
                        </div>
                    )}

                </>
            )}
        </div>
    );
}

export default PublicRooms;
