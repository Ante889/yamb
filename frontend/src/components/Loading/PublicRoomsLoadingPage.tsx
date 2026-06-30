import styles from "./PublicRoomsLoadingPage.module.css";

const ITEMS = Array.from({ length: 6 });

function PublicRoomsLoading() {
    return (
        <div className={styles.publicRooms}>
            <ul className={styles.list}>
                {ITEMS.map((_, index) => (
                    <li key={index} className={styles.row}>
                        <div className={styles.icon}></div>

                        <div className={styles.rowMain}>
                            <div className={styles.roomName}></div>
                            <div className={styles.code}></div>
                        </div>

                        <div className={styles.players}>
                            <div className={styles.player}></div>
                            <div className={styles.player}></div>
                            <div className={styles.player}></div>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    );
}

export default PublicRoomsLoading;
