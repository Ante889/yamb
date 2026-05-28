import {createSoloGameApi, exitSoloGameApi} from "../../../api/game/soloGameApi.tsx";

export const SoloGameService = {
    createSoloGame: async (numberOfDice, selectedGameType) => {
        return await createSoloGameApi(numberOfDice, selectedGameType);
    },

    exitSoloGame: async (numberOfDice) => {
        return await exitSoloGameApi(numberOfDice);
    },
};
