import { RouterProvider } from "react-router-dom";
import { router } from "./router";
import { Toaster } from "react-hot-toast";
import "./i18n/i18n.tsx";

export default function App() {
    return (
        <>
            <RouterProvider router={router} />
            <Toaster />
        </>
    );
}
