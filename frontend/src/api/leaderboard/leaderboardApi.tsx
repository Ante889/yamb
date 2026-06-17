import api from "../axios";

export const leaderboardSoloApi = async (type, dice) => {
    const res = await api.get("/solo/leaderboard/" + type + '/' + dice);

    return res.data;
};



