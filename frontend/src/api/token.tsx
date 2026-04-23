const ACCESS_TOKEN = "access_token";
const REFRESH_TOKEN = "refresh_token";
const EXPIRY = "token_expiry";

export const TokenService = {
    getAccessToken: () => localStorage.getItem(ACCESS_TOKEN),
    getRefreshToken: () => localStorage.getItem(REFRESH_TOKEN),

    setTokens: (access: string, refresh: string, expiresIn: number) => {
        const expiry = Date.now() + expiresIn * 1000;

        localStorage.setItem(ACCESS_TOKEN, access);
        localStorage.setItem(REFRESH_TOKEN, refresh);
        localStorage.setItem(EXPIRY, String(expiry));
    },

    clear: () => {
        localStorage.removeItem(ACCESS_TOKEN);
        localStorage.removeItem(REFRESH_TOKEN);
        localStorage.removeItem(EXPIRY);
    },

    isExpired: () => {
        const exp = Number(localStorage.getItem(EXPIRY));
        return !exp || Date.now() > exp;
    }
};
