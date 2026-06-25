import { useEffect, useMemo, useRef, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";
import styles from "./PublicRooms.module.css";
import {APP_PAGINATION, BASE_MERCURE_URL} from "../../../config/AppConfig.tsx";
import { MercureService } from "../../../services/mercure/mercureService.tsx";
import { RoomService } from "../../../services/game/room/roomService.tsx";
import Pagination from "../../../components/Pagination/Pagination.tsx";
import {mapMercureRoom, mapRestRoom} from "../../../mapper/roomMapper.tsx";

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
    const esRef = useRef<EventSource | null>(null);

    useEffect(() => {
        let cancelled = false;

        (async () => {
            const initial = await RoomService.getPublicRooms();
            if (cancelled) return;

            const list = Array.isArray(initial) ? initial : [];
            setRooms(list.map(mapRestRoom));
        })();

        return () => {
            cancelled = true;
        };
    }, []);

    useEffect(() => {
        let cancelled = false;

        const connect = async () => {
            const res = await MercureService.getMercureToken();
            const token = res.token;
            if (cancelled) return;

            const url = new URL(BASE_MERCURE_URL);
            url.searchParams.append("topic", "/rooms");
            url.searchParams.append("authorization", token);

            const eventSource = new EventSource(url);
            esRef.current = eventSource;

            eventSource.onmessage = (event) => {
                const data = JSON.parse(event.data);
                const incoming = mapMercureRoom(data.payload);

                setRooms((prev) => {
                    switch (data.action) {
                        case "created": {
                            if (prev.some((room) => room.id === incoming.id)) return prev;
                            return [incoming, ...prev];
                        }
                        case "updated":
                            return prev.map((room) =>
                                room.id === incoming.id ? { ...room, ...incoming } : room
                            );
                        case "removed":
                            return prev.filter((room) => room.id !== incoming.id);
                        default:
                            return prev;
                    }
                });
            };
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

    return (
        <div className={styles.publicRooms}>
            <div className={styles.header}>
                <button className={"backButton"} onClick={() => navigate(-1)}>
                    ← {t("dashboard.back")}
                </button>
                <h1>{t("publicRooms.title")}</h1>
            </div>

            <ul className={styles.list}>
                {visibleRooms.map((room) => (
                    <li
                        key={room.id}
                        className={styles.row}
                        onClick={() => navigate(`/room/${room.id}`)}
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

            <Pagination page={page} totalPages={totalPages} onPageChange={setPage} />
        </div>
    );
}

export default PublicRooms;
