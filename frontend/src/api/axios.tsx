import axios from "axios";
import { TokenService } from "./token";
import { BASE_URL } from "../config/AppConfig.tsx";
import toast from "react-hot-toast";
import i18n from "i18next";

let refreshPromise: Promise<string> | null = null;

const isAuthRoute = (url?: string) => {
    return url?.includes("/login") || url?.includes("/register") || url?.includes('/token/refresh');
};

const isAuthRouteResponse = () => {
    const path = window.location.pathname;
    return path === "/login" || path === "/register" || path === '/token/refresh';
};


const refreshAccessToken = async (): Promise<string> => {
    if (refreshPromise) {
        return refreshPromise;
    }

    refreshPromise = (async () => {
        const refreshToken = TokenService.getRefreshToken();

        const res = await axios.post(BASE_URL + "token/refresh", {
            'refresh_token': refreshToken,
        });

        const { token, refresh_token, expiresIn } = res.data;
        TokenService.setTokens(token, refresh_token, expiresIn ?? 60 * 15);

        return token;
    })();

    try {
        return await refreshPromise;
    } finally {
        refreshPromise = null;
    }
};


const api = axios.create({
    baseURL: BASE_URL,
    withCredentials: true,
});

const getErrorMessage = (data: never) => {
    return (
        data?.detail ||
        data?.message ||
        data?.description ||
        "Unknown error"
    );
};

api.interceptors.request.use(async (config) => {
    if (isAuthRoute(config.url)) {
        return config;
    }

    const token = TokenService.getAccessToken();

    if (token) {
        if (TokenService.isExpired()) {
            const newToken = await refreshAccessToken();
            config.headers.Authorization = `Bearer ${newToken}`;
            return config;
        }

        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
});


api.interceptors.response.use(
    (res) => res,
    (err) => {
        const status = err.response?.status;
        const data = err.response?.data;

        const msg = getErrorMessage(data);

        /* =========================
           NO RESPONSE (NETWORK ERROR)
        ========================= */
        if (!err.response) {
            toast.error(i18n.t("Network error. Server is unreachable."));
            return Promise.reject(err);
        }

        /* =========================
           400 - BAD REQUEST
        ========================= */
        if (status === 400) {
            toast.error(i18n.t(msg));
        }

        /* =========================
           401 - UNAUTHORIZED
        ========================= */
        if (status === 401) {
            if (!isAuthRouteResponse()) {
                TokenService.clear();
                window.location.href = "/login";
            }
            toast.error(i18n.t(msg || "Unauthorized"));
        }

        /* =========================
           403 - FORBIDDEN
        ========================= */
        if (status === 403) {
            toast.error(i18n.t(msg || "You don't have permission"));

            setTimeout(() => {
                window.location.href = "/";
            }, 300);

            return Promise.reject(err);
        }

        /* =========================
           404 - NOT FOUND
        ========================= */
        if (status === 404) {
            toast.error(i18n.t(msg || "Resource not found"));
        }

        /* =========================
           409 - CONFLICT
        ========================= */
        if (status === 409) {
            toast.error(i18n.t(msg || "Conflict error"));
        }

        /* =========================
           422 - VALIDATION ERROR
        ========================= */
        if (status === 422) {
            if (data?.errors) {
                Object.values(data.errors)
                    .flat()
                    .forEach((e: any) => {
                        toast.error(i18n.t(e));
                    });
            } else {
                toast.error(i18n.t(msg || "Validation error"));
            }
        }

        /* =========================
           500 - SERVER ERROR
        ========================= */
        if (status >= 500) {
            toast.error(i18n.t(msg || "Server error"));
        }

        return Promise.reject(err);
    }
);

export default api;
