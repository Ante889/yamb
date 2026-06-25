import api from "../axios";

export const mercureApi = async () => {
    const res = await api.get("/mercure-token");

    return res.data;
};
