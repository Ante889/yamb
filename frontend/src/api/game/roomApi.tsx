import api from "../axios";

export const getPublicRoomsApi = async () => {
    const res = await api.get("/public-rooms");

    return res.data;
};


export const createRoomApi = async (diceCount: number, gameType: string, isPublic: boolean) => {
    const res = await api.post("/rooms", {
        numberOfDice: diceCount,
        gameType,
        isPublic,
    });

    return res.data;
};

export const joinRoomWithJoinCodeApi = async (joinCode: string) => {
    const res = await api.post("/join-room/" + joinCode, {});

    return res.data;
};

export const joinRoomWithGameIdApi = async (gameId: string) => {
    const res = await api.post("/join-room-manually/" + gameId, {});

    return res.data;
};

export const lobbyHeartBeatApi = async (gameParticipantId: string) => {
    const res = await api.post("/game-participant-heart-beat/" + gameParticipantId, {});

    return res.data;
};
