import api from "../axios";

export const startDuelGameApi = async (gameId: string) => {
    const res = await api.post("/start-duel-game/" + gameId, {});

    return res.data;
};



