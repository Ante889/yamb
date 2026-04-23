import {Link, useLocation} from "react-router-dom";
import { useState } from "react";
import {APP_NAME} from "../../config/AppConfig.tsx";
import {AuthService} from "../../services/auth/authService.tsx";
import {useTranslation} from "react-i18next";

function Navbar() {
    const location = useLocation();
    const [open, setOpen] = useState(false);
    const {t} = useTranslation();
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
                    {t('dashboard.dashboard')}
                </Link>
                <Link
                    to="/"
                    className={"btn btn-danger"}
                    onClick={() => {
                        AuthService.logout();
                    }}
                >
                    {t('login.logout')}
                </Link>
            </div>
        </nav>
    );
}

export default Navbar
