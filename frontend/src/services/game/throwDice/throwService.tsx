import {throwDiceApi} from "../../../api/game/throwDiceApi.tsx";

export const ThrowService = {
    throwDiceGame: async (gameParticipantId, savedPositions) => {
        return await throwDiceApi(gameParticipantId, savedPositions);
    },
};
