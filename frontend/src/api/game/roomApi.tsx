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
