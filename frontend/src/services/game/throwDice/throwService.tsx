import {throwDiceApi, throwDiceDuelApi} from "../../../api/game/throwDiceApi.tsx";

export const ThrowService = {
    throwDiceGame: async (gameParticipantId: any, savedPositions: any) => {
        return await throwDiceApi(gameParticipantId, savedPositions);
    },
    throwDiceDuelGame: async (gameParticipantId: any, savedPositions: any) => {
        return await throwDiceDuelApi(gameParticipantId, savedPositions);
    },
};
