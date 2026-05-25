import {throwDiceApi} from "../../../api/game/throwDiceApi.tsx";

export const ThrowService = {
    createSoloGame: async (gameParticipantId, savedPositions) => {
        return await throwDiceApi(gameParticipantId, savedPositions);
    },
};
