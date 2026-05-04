import {Link, useLocation} from "react-router-dom";
import { useState } from "react";
import {AuthService} from "../../services/auth/authService.tsx";
import {useTranslation} from "react-i18next";
import leaderboardImage from "../../assets/dashboard/Logo.png";
import styles from "./Navbar.module.css";

function Navbar() {
    const location = useLocation();
    const [open, setOpen] = useState(false);
    const {t} = useTranslation();
    return (
        <nav className={styles.navbar}>
            <div className={styles.logo}>
                <img style={{width: '80px'}} src={leaderboardImage} alt="logo"/>
            </div>

            <button className={styles.burger} onClick={() => setOpen(!open)}>
                ☰
            </button>

            <div className={`${styles.links} ${open ? styles.open : ""}`}>
                <Link
                    to="/"
                    className={location.pathname === "/" ? styles.active : ""}
                    onClick={() => setOpen(false)}
                >
                    {t('dashboard.dashboard')}
                </Link>

                <Link
                    to="/"
                    className={styles.logout}
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
