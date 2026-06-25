export function parseRestPlayers(gameUsers: string | null | undefined): string[] {
    if (!gameUsers) return [];
    return gameUsers
        .split(",")
        .map((u) => u.trim())
        .filter((u) => u.length > 0);
}

export function mapRestRoom(p: any): Room {
    return {
        id: p.id,
        name: p.gameType,
        players: parseRestPlayers(p.gameUsers),
    };
}

export function mapMercureRoom(p: any): Room {
    return {
        id: p.key,
        name: p.type,
        players: (p.participants ?? [])
            .map((part: any) => part.username)
            .filter((u: any): u is string => typeof u === "string" && u.length > 0),
    };
}
