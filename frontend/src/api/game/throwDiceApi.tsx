import api from "../axios";

export const throwDiceApi = async (gameParticipantId: any, savedPositions: any) => {
    const res = await api.post("/throw-dice/" + gameParticipantId, {
        savedPositions: savedPositions,
    });

    return res.data;
};

export const throwDiceDuelApi = async (gameParticipantId: any, savedPositions: any) => {
    const res = await api.post("/throw-dice-duel/" + gameParticipantId, {
        savedPositions: savedPositions,
    });

    return res.data;
};



