import {Link, useLocation} from "react-router-dom";
import { useState } from "react";
import {AuthService} from "../../services/auth/authService.tsx";
import {useTranslation} from "react-i18next";
import leaderboardImage from "../../assets/dashboard/Logo.png";

function Navbar() {
    const location = useLocation();
    const [open, setOpen] = useState(false);
    const {t} = useTranslation();
    return (
        <nav className="navbar">
            <div className="logo">
                <img style={{width: '80px'}} src={leaderboardImage} alt="logo"/>
            </div>

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
                    className="logout"
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
