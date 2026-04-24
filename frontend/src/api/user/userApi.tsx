import api from "../axios";

export const getCurrentUserData = async () => {
    const res = await api.get("/user/me");

    return res.data;
};



