import api from "../axios";

export const scoreEntryApi = async (gameParticipantId) => {
    const res = await api.get("/score-entry-by-participant/" + gameParticipantId);

    return res.data;
};



