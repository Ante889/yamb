import styles from "./JambLoading.module.css";
import {useTranslation} from "react-i18next";

export function JambLoading() {
    const { t } = useTranslation();

    return (
        <div className={styles.sheetWrap}>
            <div className={styles.sheet}>

                <div className={styles.loadingTitle}>
                    {t('Loading')}
                </div>

                {[1, 2, 3, 4, 5].map((row) => (
                    <div className={styles.row} key={row}>
                        <div className={`${styles.cell} ${styles.skeleton}`} />
                        <div className={`${styles.cell} ${styles.skeleton}`} />
                        <div className={`${styles.cell} ${styles.skeleton}`} />
                        <div className={`${styles.cell} ${styles.skeleton}`} />
                        <div className={`${styles.cell} ${styles.skeleton}`} />
                    </div>
                ))}
            </div>
        </div>
    );
}
