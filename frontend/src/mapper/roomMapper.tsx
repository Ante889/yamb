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

        case "removed":
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
    if (action === "removed") {
        return null;
    }

    if (!prevRoom || prevRoom.key === incoming.key) {
        return incoming;
    }

    return prevRoom;
}
