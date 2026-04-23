import { Navigate, Outlet } from "react-router-dom";
import { TokenService } from "../../api/token.tsx";

export default function PublicOnlyRoute() {
    const token = TokenService.getAccessToken();
    const isAuth = !!token && !TokenService.isExpired();

    if (isAuth) {
        return <Navigate to="/" replace />;
    }

    return <Outlet />;
}
