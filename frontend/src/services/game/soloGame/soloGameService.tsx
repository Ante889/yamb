import {createSoloGameApi} from "../../../api/game/soloGameApi.tsx";

export const SoloGameService = {
    createSoloGame: async (numberOfDice) => {
        return await createSoloGameApi(numberOfDice);
    },
};
