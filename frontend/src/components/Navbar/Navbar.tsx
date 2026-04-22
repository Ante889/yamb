import { Link, useLocation } from "react-router-dom";
import { useState } from "react";
import {APP_NAME} from "../../config/AppConfig.tsx";

function Navbar() {
    const location = useLocation();
    const [open, setOpen] = useState(false);

    return (
        <nav className="navbar">
            <div className="logo">{APP_NAME}</div>

            <button className="burger" onClick={() => setOpen(!open)}>
                ☰
            </button>

            <div className={`links ${open ? "open" : ""}`}>
                <Link
                    to="/"
                    className={location.pathname === "/" ? "active" : ""}
                    onClick={() => setOpen(false)}
                >
                    Home
                </Link>
            </div>
        </nav>
    );
}

export default Navbar
