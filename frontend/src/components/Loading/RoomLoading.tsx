import styles from "./RoomLoading.module.css";
import { useTranslation } from "react-i18next";

function RoomLoading() {
    const { t } = useTranslation();

    return (
        <div className={styles.wrapper}>
            <div className={styles.card}>

                <div className={styles.spinner} />

                <p className={styles.text}>
                    {t("room.loadingRoom")}
                </p>

                <span className={styles.subtext}>
                    Connecting to lobby...
                </span>

            </div>
        </div>
    );
}

export default RoomLoading;
