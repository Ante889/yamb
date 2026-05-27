import api from "../axios";

export const createSoloGameApi = async (numberOfDice) => {
    const res = await api.post("/start-solo-game", {
        numberOfDice: numberOfDice,
    });

    return res.data;
};

export const exitSoloGameApi = async (gameParticipantId) => {
    const res = await api.delete("/end-solo-game/" + gameParticipantId);

    return res.data;
};



