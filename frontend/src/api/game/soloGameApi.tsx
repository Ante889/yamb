import api from "../axios";

export const createSoloGameApi = async (numberOfDice, selectedGameType) => {
    const res = await api.post("/start-solo-game", {
        numberOfDice: numberOfDice,
        gameType: selectedGameType
    });

    return res.data;
};

export const exitSoloGameApi = async (gameParticipantId) => {
    const res = await api.delete("/end-solo-game/" + gameParticipantId);

    return res.data;
};



