import { useEffect, useState } from "react";
import { JambLoading } from "../../components/Loading/JambLoading";
import styles from "./Leaderboard.module.css";
import {
    CLASSIC_GAME_TYPE,
    SINGLE_COLUMN_GAME_TYPE,
    YAMB_PLUS_GAME_TYPE,
} from "../../constants/GameType.tsx";
import { useTranslation } from "react-i18next";

const GAME_MODES = [
    { id: SINGLE_COLUMN_GAME_TYPE },
    { id: CLASSIC_GAME_TYPE },
    { id: YAMB_PLUS_GAME_TYPE },
];

export default function Leaderboard() {
    const [isLoading, setIsLoading] = useState(true);
    const [activeTab, setActiveTab] = useState(SINGLE_COLUMN_GAME_TYPE);
    const [leaderboard, setLeaderboard] = useState([]);
    const { t } = useTranslation();

    useEffect(() => {
        const loadGameData = async () => {
            setIsLoading(true);

            try {
                setLeaderboard([
                    { id: 1, username: "Ante", score: 1245 },
                    { id: 2, username: "Marko", score: 1190 },
                    { id: 3, username: "Ivan", score: 1155 },
                    { id: 4, username: "Nikola", score: 1088 },
                    { id: 5, username: "Luka", score: 1051 },
                    { id: 6, username: "Petar", score: 1002 },
                ]);
            } finally {
                setIsLoading(false);
            }
        };

        loadGameData();
    }, [activeTab]);

    if (isLoading) {
        return <JambLoading />;
    }

    return (
        <div className={styles.leaderboardPage}>
            <br/>
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
                {leaderboard.map((player, index) => (
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
                            {player.score}
                        </span>
                    </div>
                ))}
            </div>
        </div>
    );
}
