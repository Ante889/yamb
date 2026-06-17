import styles from "./LeaderboardLoading.module.css";

export function LeaderboardSkeleton() {
    return (
        <div className={`${styles.leaderboardList} card`}>
            {Array.from({ length: 8 }).map((_, i) => (
                <div key={i} className={styles.skeletonRow}>
                    <div className={styles.leaderboardLeft}>
                        <div className={styles.skeletonRank} />
                        <div className={styles.skeletonName} />
                    </div>

                    <div className={styles.skeletonScore} />
                </div>
            ))}
        </div>
    );
}
