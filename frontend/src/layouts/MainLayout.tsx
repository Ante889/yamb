import Navbar from "../components/Navbar/Navbar.tsx";
import { Outlet } from "react-router-dom";
import Footer from "../components/Footer/Footer.tsx";

export default function MainLayout() {
    return (
        <div className="app">
            <header>
                <Navbar/>
            </header>
            <main className="content">
                <Outlet/>
            </main>
            <footer>
                <Footer/>
            </footer>
        </div>
    );
}
