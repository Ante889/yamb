import {getGameByIdApi} from "../../../api/game/gameApi.tsx";

export const GameService = {
    getGameById: async (id) => {
        return await getGameByIdApi(id);
    },
};
