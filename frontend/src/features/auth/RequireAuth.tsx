import { Navigate, Outlet } from "react-router-dom";

export default function RequireAuth() {
    const isAuth = false;

    if (!isAuth) {
        return <Navigate to="/login" replace />;
    }

    return <Outlet />;
}
