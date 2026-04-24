import { Navigate, Outlet } from "react-router-dom";
import { TokenService } from "../../api/token.tsx";

export default function RequireAuth() {
    const token = TokenService.getAccessToken();

    const isAuth = !!token;

    if (!isAuth) {
        TokenService.clear();
        return <Navigate to="/login" replace />;
    }

    return <Outlet />;
}
