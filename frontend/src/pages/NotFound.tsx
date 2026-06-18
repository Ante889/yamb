import styles from "./NotFound.module.css";
import {useTranslation} from "react-i18next";

export default function NotFound() {
    const { t } = useTranslation();

    return (
        <div className={styles.wrapper}>
            <div className={`card`}>
                <h1 className={styles.title}>404</h1>

                <p className={styles.text}>
                    {t('error.404')}
                </p>
            </div>
        </div>
    );
}
