import api from "../axios";

export const getCurrentUserData = async () => {
    const res = await api.get("/user/me");

    return res.data;
};


export const getUserStatistic = async (id) => {
    const res = await api.get("/user/statistics/" + id);

    return res.data;
};



