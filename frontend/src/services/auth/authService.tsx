import {loginApi, registerApi} from "../../api/auth/authApi.tsx";
import { TokenService } from "../../api/token";

export const AuthService = {
    login: async (email: string, password: string) => {
        const data = await loginApi(email, password);

        const { token, refresh_token, expiresIn } = data;

        TokenService.setTokens(
            token,
            refresh_token,
            expiresIn ?? 60 * 15
        );

        return data;
    },

    logout: () => {
        TokenService.clear();
        window.location.href = "/login";
    },

    register: async (username: string, email: string, password: string, repeatPassword: string) => {
        return await registerApi(username, email, password, repeatPassword);
    },
};
