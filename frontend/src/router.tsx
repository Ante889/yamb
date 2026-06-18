import { createBrowserRouter, type RouteObject } from "react-router-dom";

import MainLayout from "./layouts/MainLayout";
import PublicLayout from "./layouts/PublicLayout";
import RequireAuth from "./features/auth/RequireAuth";

import Login from "./pages/auth/Login";
import PublicOnlyRoute from "./features/auth/PublicOnlyRoute.tsx";
import Dashboard from "./pages/dashboard/Dashboard.tsx";
import SoloGame from "./pages/game/SoloGame.tsx";
import Leaderboard from "./pages/leaderboard/Leaderboard.tsx";
import RoomList from "./pages/duel/RoomList.tsx";
import NotFound from "./pages/NotFound.tsx";
import PublicRooms from "./pages/duel/publicRooms/PublicRooms.tsx";

const routes: RouteObject[] = [
    {
        element: <PublicOnlyRoute />,
        children: [
            {
                element: <PublicLayout />,
                children: [
                    {
                        path: "/login",
                        element: <Login />,
                    },
                ],
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
    {
        element: <RequireAuth />,
        children: [
            {
                element: <MainLayout />,
                children: [
                    {
                        path: "/solo-game/:id",
                        element: <SoloGame />,
                    },
                    {
                        path: "/leaderboard",
                        element: <Leaderboard />,
                    },
                    {
                        path: "/room-list",
                        element: <RoomList />,
                    },
                    {
                        path: "/public-rooms",
                        element: <PublicRooms />,
                    },
                    {
                        path: "*",
                        element: <NotFound />
                    }
                ],
            },
        ],
    },
];

export const router = createBrowserRouter(routes);
