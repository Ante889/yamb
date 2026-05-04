import { useState } from "react";
import styles from "./Auth.module.css";
import toast from "react-hot-toast";
import { useTranslation } from "react-i18next";
import { useNavigate } from "react-router-dom";
import { AuthService } from "../../services/auth/authService.tsx";
import { UserService } from "../../services/user/userService.tsx";
import { AuthCard } from "../../components/Auth/AuthCard.tsx";
import { AuthInput } from "../../components/Auth/AuthInput.tsx";
import yambText from "../../assets/dashboard/Yamb.png";

function Auth() {
    const { t } = useTranslation();
    const navigate = useNavigate();

    const [mode, setMode] = useState<"login" | "register">("login");
    const [username, setUsername] = useState("");
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [repeatPassword, setRepeatPassword] = useState("");

    const handleSubmit = async () => {
        const loadingToast = toast.loading(t("loading"));

        try {
            if (mode === "login") {
                const res = await AuthService.login(email, password);

                if (res) {
                    await UserService.getCurrentUserData();
                    toast.success(t("login.loginSuccess"));
                    navigate("/");
                }
            } else {
                const res = await AuthService.register(
                    username,
                    email,
                    password,
                    repeatPassword
                );

                if (res) {
                    toast.success(t("login.registrationSuccess"));
                    setMode("login");
                }
            }
        } finally {
            toast.dismiss(loadingToast);
        }
    };

    return (
        <div className={styles.loginPage}>
            <AuthCard>
                <div className={styles.loginCard}>
                    <img
                        style={{ width: "280px", margin: "0 auto" }}
                        src={yambText}
                        alt="yamb-text"
                    />

                    <form
                        onSubmit={(e) => {
                            e.preventDefault();
                            handleSubmit();
                        }}
                    >
                        <div className={styles.authTabs}>
                            <button
                                type="button"
                                className={
                                    mode === "login" ? styles.active : ""
                                }
                                onClick={() => setMode("login")}
                            >
                                {t("login.login")}
                            </button>

                            <button
                                type="button"
                                className={
                                    mode === "register" ? styles.active : ""
                                }
                                onClick={() => setMode("register")}
                            >
                                {t("login.register")}
                            </button>
                        </div>

                        {mode === "register" && (
                            <AuthInput
                                value={username}
                                onChange={(e) =>
                                    setUsername(e.target.value)
                                }
                                placeholder={t("login.username")}
                            />
                        )}

                        <AuthInput
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            placeholder={t("login.email")}
                        />

                        <AuthInput
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder={t("login.password")}
                        />

                        {mode === "register" && (
                            <AuthInput
                                type="password"
                                value={repeatPassword}
                                onChange={(e) =>
                                    setRepeatPassword(e.target.value)
                                }
                                placeholder={t("login.repeatPassword")}
                            />
                        )}

                        <button
                            className={styles.submitBtn}
                            type="submit"
                        >
                            {mode === "login"
                                ? t("login.loginBtn")
                                : t("login.registerBtn")}
                        </button>
                    </form>
                </div>
            </AuthCard>
        </div>
    );
}

export default Auth;
