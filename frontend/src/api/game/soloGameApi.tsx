import api from "../axios";

export const createSoloGameApi = async (numberOfDice) => {
    const res = await api.post("/start-solo-game", {
        numberOfDice: numberOfDice,
    });

    return res.data;
};



