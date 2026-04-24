const ACCESS_TOKEN = "access_token";
const REFRESH_TOKEN = "refresh_token";
const EXPIRY = "token_expiry";
const USER_NAME = "user_name";
const USER_EMAIL = "user_email";

export const TokenService = {
    getAccessToken: () => localStorage.getItem(ACCESS_TOKEN),
    getRefreshToken: () => localStorage.getItem(REFRESH_TOKEN),
    getUsername: () => localStorage.getItem(USER_NAME),
    getUserEmail: () => localStorage.getItem(USER_EMAIL),
    setTokens: (access: string, refresh: string, expiresIn: number) => {
        const expiry = Date.now() + expiresIn * 1000;

        localStorage.setItem(ACCESS_TOKEN, access);
        localStorage.setItem(REFRESH_TOKEN, refresh);
        localStorage.setItem(EXPIRY, String(expiry));
    },
    setUserData:(username: string, email: string) => {
        localStorage.setItem(USER_NAME, username);
        localStorage.setItem(USER_EMAIL, email);
    },
    clear: () => {
        localStorage.removeItem(ACCESS_TOKEN);
        localStorage.removeItem(REFRESH_TOKEN);
        localStorage.removeItem(EXPIRY);
        localStorage.removeItem(USER_NAME);
        localStorage.removeItem(USER_EMAIL);
    },
    isExpired: () => {
        const exp = Number(localStorage.getItem(EXPIRY));
        return !exp || Date.now() > exp;
    }
};
