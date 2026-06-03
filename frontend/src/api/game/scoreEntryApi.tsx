import api from "../axios";

export const scoreEntryApi = async (gameParticipantId) => {
    const res = await api.get("/score-entry-by-participant/" + gameParticipantId);

    return res.data;
};

export const newEntryApi = async (gameParticipantId, data) => {
    const res = await api.post("/enter-new-entry/" + gameParticipantId, data);

    return res.data;
};

export const setAnnouncementApi = async (gameParticipantId, data) => {
    const res = await api.post("/set-announcement/" + gameParticipantId, data);

    return res.data;
};


