import {useState} from "react";
import "./Auth.css";
import toast from "react-hot-toast";
import {useTranslation} from "react-i18next";
import {APP_NAME} from "../../config/AppConfig.tsx";
import {AuthCard} from "../../components/Auth/AuthCard.tsx";
import {AuthInput} from "../../components/Auth/AuthInput.tsx";
import {useNavigate} from "react-router-dom";
import {AuthService} from "../../services/auth/authService.tsx";
import {UserService} from "../../services/user/userService.tsx";
import yambText from "../../assets/dashboard/Yamb.png";

function Auth() {
    const {t} = useTranslation();
    const [mode, setMode] = useState<"login" | "register">("login");
    const navigate = useNavigate();
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
                    await UserService.getCurrentUserData()
                    toast.success(t("login.loginSuccess"));
                    navigate("/");
                }
            } else {
                const res = await AuthService.register(username, email, password, repeatPassword);
                if (res) {
                    toast.success(t("login.registrationSuccess"));
                    setMode('login');
                }
            }
        } finally {
            toast.dismiss(loadingToast);
        }
    };

    return (
        <AuthCard>
            <img style={{width: '280px'}} src={yambText} alt="yamb-text"/>
            <form onSubmit={(e) => {
                e.preventDefault();
                handleSubmit();
            }}>
                <div className="auth-tabs">
                    <button
                        className={mode === "login" ? "active" : ""}
                        onClick={() => setMode("login")}
                        type="button"
                    >
                        {t("login.login")}
                    </button>

                    <button
                        className={mode === "register" ? "active" : ""}
                        onClick={() => setMode("register")}
                        type="button"
                    >
                        {t("login.register")}
                    </button>
                </div>

                {mode === "register" && (
                    <AuthInput
                        type="username"
                        value={username}
                        onChange={(e) => setUsername(e.target.value)}
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
                        onChange={(e) => setRepeatPassword(e.target.value)}
                        placeholder={t("login.repeatPassword")}
                    />
                )}

                <button style={{width: '100%', marginTop: '10px'}} className={'btn btn-primary'} type="submit">
                    {mode === "login"
                        ? t("login.loginBtn")
                        : t("login.registerBtn")}
                </button>
            </form>
        </AuthCard>
    );
}

export default Auth;
