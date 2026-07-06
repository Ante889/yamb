import api from "../axios";

export const getChatByGameIdApi = async (gameId) => {
    const res = await api.get("/chat/" + gameId);

    return res.data;
};

export const setChatByGameParticipantIdApi = async (gameParticipantId, data) => {
    const res = await api.post("/chat/" + gameParticipantId, data);

    return res.data;
};
