import {createSoloGameApi, exitSoloGameApi} from "../../../api/game/soloGameApi.tsx";

export const SoloGameService = {
    createSoloGame: async (numberOfDice) => {
        return await createSoloGameApi(numberOfDice);
    },

    exitSoloGame: async (numberOfDice) => {
        return await exitSoloGameApi(numberOfDice);
    },
};
