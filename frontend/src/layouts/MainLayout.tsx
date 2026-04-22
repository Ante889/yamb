import Navbar from "../components/Navbar/Navbar.tsx";
import { Outlet } from "react-router-dom";

export default function MainLayout() {
    return (
        <div className="app">
            <Navbar />

            <main className="content">
                <Outlet />
            </main>
        </div>
    );
}
