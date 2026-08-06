import room from "../pages/duel/room/Room.tsx";
import {useNavigate} from "react-router-dom";

export function parseRestPlayers(gameUsers: string | null | undefined): string[] {
    if (!gameUsers) return [];
    return gameUsers
        .split(",")
        .map((u) => u.trim())
        .filter((u) => u.length > 0);
}

export function mapRestRoom(p: any): any {
    return {
        id: p.id,
        name: p.gameType,
        players: parseRestPlayers(p.gameUsers),
    };
}

export function mapMercureRoom(p: any): any {
    return {
        id: p.key,
        name: p.type,
        players: (p.participants ?? [])
            .map((part: any) => part.username)
            .filter((u: any): u is string => typeof u === "string" && u.length > 0),
    };
}

export function applyRoomEvent(
    rooms: any[],
    action: string,
    incoming: any
): any[] {
    switch (action) {
        case "created":
            return rooms.some((room) => room.id === incoming.id)
                ? rooms
                : [incoming, ...rooms];

        case "updated":
            return rooms.map((room) =>
                room.id === incoming.id
                    ? { ...room, ...incoming }
                    : room
            );

        case "deleted":
            return rooms.filter(
                (room) => room.id !== incoming.id
            );

        default:
            return rooms;
    }
}

export function applyEventForLobbyRoom(
    prevRoom: any,
    action: string,
    incoming: any
): any {
    if (!prevRoom || prevRoom.key === incoming.key) {
        if (action === "deleted") {
            return null
        }

        return incoming;
    }

    return prevRoom;
}

export function applyEventForGameStarted(
    prevRoom: any,
    action: string,
    incoming: any
): any {
    if (!prevRoom || prevRoom.key === incoming.key) {
        if (action !== "created") {
            return null
        }

        return true;
    }

    return null;
}
