import { useEffect, useState } from "react";
import styles from "./Leaderboard.module.css";
import {
    CLASSIC_GAME_TYPE,
    SINGLE_COLUMN_GAME_TYPE,
    YAMB_PLUS_GAME_TYPE,
} from "../../constants/GameType.tsx";
import { useTranslation } from "react-i18next";
import {LeaderboardService} from "../../services/leaderboard/leaderboardSevice.tsx";
import {LeaderboardSkeleton} from "../../components/Loading/LeaderboardLoading.tsx";

const GAME_MODES = [
    { id: SINGLE_COLUMN_GAME_TYPE },
    { id: CLASSIC_GAME_TYPE },
    { id: YAMB_PLUS_GAME_TYPE },
];

const DICE_NUMBER = [
    { id: 5 },
    { id: 6 }
];

export default function Leaderboard() {
    const [isLoading, setIsLoading] = useState(true);
    const [activeTab, setActiveTab] = useState(SINGLE_COLUMN_GAME_TYPE);
    const [leaderboard, setLeaderboard] = useState([]);
    const [dice, setDice] = useState(5);
    const { t } = useTranslation();

    useEffect(() => {
        const loadGameData = async () => {
            setIsLoading(true);

            try {
                const response =
                    await LeaderboardService.leaderboardSolo(
                        activeTab, dice
                    );

                if (!response) return;

                setLeaderboard(response);
            } finally {
                setIsLoading(false);
            }
        };

        loadGameData();
    }, [activeTab, dice]);

    return (
        <div className={styles.leaderboardPage}>
            <br/>
            <div className={styles.leaderboardTabs}>
                {DICE_NUMBER.map((d) => (
                    <button
                        key={d.id}
                        className={`${styles.leaderboardTab} ${
                            dice === d.id ? styles.active : ""
                        }`}
                        onClick={() => setDice(d.id)}
                    >
                        {t(`leaderboard.dice_${d.id}`)}
                    </button>
                ))}
            </div>
            <div className={styles.leaderboardTabs}>
                {GAME_MODES.map((tab) => (
                    <button
                        key={tab.id}
                        className={`${styles.leaderboardTab} ${
                            activeTab === tab.id ? styles.active : ""
                        }`}
                        onClick={() => setActiveTab(tab.id)}
                    >
                        {t(`leaderboard.${tab.id}`)}
                    </button>
                ))}
            </div>

            <br/>

            <div className={`${styles.leaderboardList} card`}>
                {isLoading ? (
                    <LeaderboardSkeleton/>
                ) : (
                    leaderboard.length > 0? (
                        leaderboard.map((player, index) => (
                            <div
                                key={player.id}
                                className={`${styles.leaderboardRow} ${
                                    index < 3 ? styles.topPlayer : ""
                                }`}
                            >
                                <div className={styles.leaderboardLeft}>
                                <span className={styles.leaderboardRank}>
                                    #{index + 1}
                                </span>

                                    <span className={styles.leaderboardName}>
                                    {player.username}
                                </span>
                                </div>

                                <span className={styles.leaderboardScore}>
                                {player.points}
                            </span>
                            </div>
                        ))
                    ) : (
                        <div className={styles.noData}>
                            <div className={styles.noDataIcon}>🎲</div>
                            <div className={styles.noDataText}>
                                {t("leaderboard.no_data")}
                            </div>
                        </div>
                    )
                )}
            </div>
        </div>
    );
}
