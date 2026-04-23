import api from "../axios";

export const loginApi = async (email: string, password: string) => {
    const res = await api.post("/login", {
        email,
        password,
    });

    return res.data;
};

export const registerApi = async (username: string, email: string, password: string, repeatPassword: string) => {
    const res = await api.post("/register", {
        username,
        email,
        password,
        repeatPassword
    });

    return res.data;
};


