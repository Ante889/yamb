import {leaderboardSoloApi} from "../../api/leaderboard/leaderboardApi.tsx";

export const LeaderboardService = {
    leaderboardSolo: async (type, dice) => {
        return await leaderboardSoloApi(type, dice);
    },
};
