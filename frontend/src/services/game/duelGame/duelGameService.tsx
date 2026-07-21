import {startDuelGameApi} from "../../../api/game/duelGameApi.tsx";

export const DuelGameService = {
    startGame: async (gameId: string) => {
        return await startDuelGameApi(gameId);
    }
};
