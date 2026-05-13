import { useState } from "react";
import Modal from "../../../components/Modal/Modal.tsx";
import styles from "./CreateSoloGame.module.css";
import {useTranslation} from "react-i18next";
import toast from "react-hot-toast";
import {SoloGameService} from "../../../services/game/soloGame/soloGameService.tsx";

function CreateSoloGame({ isModalOpen, setIsModalOpen }) {
    const { t } = useTranslation();
    const [selectedDice, setSelectedDice] = useState(6);

    const handleStart = async () => {
        const loadingToast = toast.loading(t("loading"));

        try {
            const gameData = await SoloGameService.createSoloGame(selectedDice);
            console.log(gameData)
        } finally {
            toast.dismiss(loadingToast);
        }
    };

    return (
        <Modal
            isOpen={isModalOpen}
            onClose={() => setIsModalOpen(false)}
            title={t("game.createSoloGame")}
        >
            <div className={styles.soloGame}>
                <div className={styles.section}>
                    <p className={styles.label}>
                        {t("game.choiceNumberOfDice")}
                    </p>

                    <div className={styles.options}>
                        {[5, 6].map((dice) => (
                            <button
                                key={dice}
                                className={`${styles.card} ${
                                    selectedDice === dice
                                        ? styles.active
                                        : ""
                                }`}
                                onClick={() => setSelectedDice(dice)}
                            >
                                <div className={styles.dice}>
                                    {Array.from({length: dice}).map((_, index) => (
                                        <div key={index} className={styles.diceCube}>
                                            <div
                                                className={`${styles.diceFace} ${
                                                    styles[`face${index + 1}`]
                                                }`}
                                            >
                                                {Array.from({length: index + 1}).map((_, dotIndex) => (
                                                    <span
                                                        key={dotIndex}
                                                        className={styles.dot}
                                                    />
                                                ))}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </button>
                        ))}
                    </div>
                </div>

                <button
                    className={'btn btn-primary'}
                    onClick={() => handleStart()}
                >
                    {t("game.startGame")}
                </button>
            </div>
        </Modal>
    );
}

export default CreateSoloGame;
