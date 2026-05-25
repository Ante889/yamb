import api from "../axios";

export const throwDiceApi = async (gameParticipantId, savedPositions) => {
    const res = await api.post("/throw-dice/" + gameParticipantId, {
        savedPositions: savedPositions,
    });

    return res.data;
};



