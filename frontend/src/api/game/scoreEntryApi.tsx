import api from "../axios";

export const scoreEntryApi = async (gameParticipantId: any) => {
    const res = await api.get("/score-entry-by-participant/" + gameParticipantId);

    return res.data;
};


export const scoreDuelEntryApi = async (gameId: any) => {
    const res = await api.get("/score-duel-entry-by-game/" + gameId);

    return res.data;
};

export const newEntryApi = async (gameParticipantId: any, data: any) => {
    const res = await api.post("/enter-new-entry/" + gameParticipantId, data);

    return res.data;
};

export const newDuelEntryApi = async (gameId: string, gameParticipantId: string, data: any) => {
    const res = await api.post("/enter-new-duel-entry/" + gameParticipantId + '/' + gameId, data);

    return res.data;
};

export const setAnnouncementApi = async (gameParticipantId: any, data: any) => {
    const res = await api.post("/set-announcement/" + gameParticipantId, data);

    return res.data;
};


