import { createBrowserRouter, type RouteObject } from "react-router-dom";

import MainLayout from "./layouts/MainLayout";
import PublicLayout from "./layouts/PublicLayout";
import RequireAuth from "./features/auth/RequireAuth";

import Dashboard from "./pages/dashboard/Dashboard";
import Login from "./pages/auth/Login";

const routes: RouteObject[] = [
    {
        element: <PublicLayout />,
        children: [
            {
                path: "/login",
                element: <Login />,
            },
        ],
    },
    {
        element: <RequireAuth />,
        children: [
            {
                element: <MainLayout />,
                children: [
                    {
                        path: "/",
                        element: <Dashboard />,
                    },
                ],
            },
        ],
    },
];

export const router = createBrowserRouter(routes);
