import { useState } from "react";
import Modal from "../Modal/Modal.tsx";
import styles from "./CreateGameModal.module.css";
import { useTranslation } from "react-i18next";
import classicGame from "../../assets/dashboard/Classic_game.png";
import yambPlus from "../../assets/dashboard/YambPlus2.png";
import singleColumn from "../../assets/dashboard/Single_column.png";
import { CLASSIC_GAME_TYPE, SINGLE_COLUMN_GAME_TYPE, YAMB_PLUS_GAME_TYPE } from "../../constants/GameType.tsx";

interface GameConfig {
    diceCount: number;
    gameType: string;
    isPublic: boolean;
}

interface Props {
    isOpen: boolean;
    onClose: () => void;
    title: string;
    submitLabel: string;
    showVisibility?: boolean;
    onSubmit: (config: GameConfig) => void;
}

const gameTypes = [
    { id: SINGLE_COLUMN_GAME_TYPE, image: singleColumn },
    { id: CLASSIC_GAME_TYPE, image: classicGame },
    { id: YAMB_PLUS_GAME_TYPE, image: yambPlus },
];

function CreateGameModal({ isOpen, onClose, title, submitLabel, showVisibility = false, onSubmit }: Props) {
    const { t } = useTranslation();

    const [diceCount, setDiceCount] = useState(6);
    const [gameType, setGameType] = useState(CLASSIC_GAME_TYPE);
    const [isPublic, setIsPublic] = useState(true);

    const handleSubmit = () => {
        onSubmit({ diceCount, gameType, isPublic });
    };

    return (
        <Modal isOpen={isOpen} onClose={onClose} title={title}>
            <div className={styles.form}>
                <div className={styles.section}>
                    <p className={styles.label}>{t("game.choiceNumberOfDice")}</p>

                    <div className={styles.options}>
                        {[5, 6].map((n) => (
                            <button
                                key={n}
                                className={`card ${styles.cardMobile} ${diceCount === n ? styles.active : ""}`}
                                onClick={() => setDiceCount(n)}
                            >
                                <div className={styles.dice}>
                                    {Array.from({ length: n }).map((_, i) => (
                                        <div key={i} className={styles.diceCube}>
                                            <div className={`${styles.diceFace} ${styles[`face${i + 1}`]}`}>
                                                {Array.from({ length: i + 1 }).map((_, d) => (
                                                    <span key={d} className={styles.dot} />
                                                ))}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </button>
                        ))}
                    </div>
                </div>

                <div className={styles.section}>
                    <p className={styles.label}>{t("game.choiceGameType")}</p>

                    <div className={styles.gameTypeOptions}>
                        {gameTypes.map((g) => (
                            <div
                                key={g.id}
                                className={`${styles.gameTypeCard} ${gameType === g.id ? styles.gameTypeActive : ""}`}
                                onClick={() => setGameType(g.id)}
                            >
                                <img src={g.image} alt={g.id} className={styles.gameTypeImage} />
                            </div>
                        ))}
                    </div>
                </div>

                {showVisibility && (
                    <div className={styles.section}>
                        <p className={styles.label}>{t("roomList.visibility")}</p>

                        <div className={styles.visibilityOptions}>
                            <div
                                className={`${styles.visibilityCard} ${isPublic ? styles.visibilityActive : ""}`}
                                onClick={() => setIsPublic(true)}
                            >
                                <span className={styles.visibilityIcon}>🌍</span>
                                <span className={styles.visibilityLabel}>{t("roomList.public")}</span>
                            </div>

                            <div
                                className={`${styles.visibilityCard} ${!isPublic ? styles.visibilityActive : ""}`}
                                onClick={() => setIsPublic(false)}
                            >
                                <span className={styles.visibilityIcon}>🔒</span>
                                <span className={styles.visibilityLabel}>{t("roomList.private")}</span>
                            </div>
                        </div>
                    </div>
                )}

                <button className="btn btn-primary" onClick={handleSubmit}>
                    {submitLabel}
                </button>
            </div>
        </Modal>
    );
}

export default CreateGameModal;
