import api from "../axios";

export const getGameByIdApi = async (id) => {
    const res = await api.get("/game/" + id);

    return res.data;
};
