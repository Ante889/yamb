import { Navigate, Outlet } from "react-router-dom";
import { TokenService } from "../../api/token.tsx";

export default function RequireAuth() {
    const token = TokenService.getAccessToken();
    const isExpired = TokenService.isExpired();

    const isAuth = !!token && !isExpired;

    if (!isAuth) {
        TokenService.clear();
        return <Navigate to="/login" replace />;
    }

    return <Outlet />;
}
