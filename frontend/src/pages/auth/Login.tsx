import { useState } from "react";
import "./Login.css";
import {rollDiceLogin} from "../../features/auth/RollDiceLogin.tsx";
import toast from "react-hot-toast";
import {useTranslation} from "react-i18next";

function Login() {
    const {t} = useTranslation();
    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");
    const [rolling, setRolling] = useState(false);
    const [dice, setDice] = useState([1, 1, 1, 1, 1, 1]);

    const rollDice = async () => {
        setRolling(true);
        const result = await rollDiceLogin(username, password);

        let count = 0;

        const interval = setInterval(() => {
            setDice(
                Array.from({ length: 6 }, () =>
                    Math.floor(Math.random() * 6) + 1
                )
            );

            count++;

            if (count > 10) {
                clearInterval(interval);
                setDice(result);
                setRolling(false);
            }
        }, 100);
    };

    const handleLogin = () => {
        setRolling(true);

        if (!username.trim()) {
            toast.error(t('login.errorUser'));
            return;
        }

        if (password.length < 4) {
            toast.error(t('login.errorPass'));
            return;
        }

        rollDice();
    };

    return (
        <div className="login-page">
            <div className="login-card">
                <h1 className="title"> 🎲 YAMB - {t('login.login')}</h1>
                <div className={`dice-row ${rolling ? "rolling" : ""}`}>
                    {dice.map((d, i) => (
                        <div key={i} className="dice-face">
                            {d}
                        </div>
                    ))}
                </div>

                <input
                    value={username}
                    onChange={(e) => setUsername(e.target.value)}
                    placeholder={t("login.username")}
                />

                <input
                    type="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    placeholder={t("login.password")}
                />

                <button onClick={handleLogin}>
                    {t('login.loginBtn')}
                </button>

            </div>
        </div>
    );
}

export default Login;
