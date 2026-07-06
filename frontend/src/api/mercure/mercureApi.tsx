import api from "../axios";

export const mercureApi = async (gameId?: number) => {
    const url = gameId
        ? `/mercure-token?gameId=${gameId}`
        : `/mercure-token`;

    const res = await api.get(url);

    return res.data;
};
