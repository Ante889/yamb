import { useState, useEffect } from "react";
import styles from "./DiceRoller.module.css";
import { useTranslation } from "react-i18next";
import { ThrowService } from "../../services/game/throwDice/throwService.tsx";
import { useParams } from "react-router-dom";
import {DUEL_GAME, SINGLE_GAME} from "../../constants/GamePlayerTypes.tsx";

type Dice = {
    value: number | null;
    held: boolean;
};

type Props = {
    diceCount?: number;
    dices?: number[];
    throwCount?: number;
    setThrowCount?: any;
    type: string;
    gameParticipantId?: any;
    me?: any,
    activePlayer?: any
};

const MAX_THROWS = 3;

export function DiceRoller({
                               diceCount = 5,
                               dices = [],
                               throwCount,
                               setThrowCount,
                               type,
                               gameParticipantId = null,
                               me = null,
                               activePlayer = null
                           }: Props) {
    const { t } = useTranslation();
    const { id } = useParams();

    const [dice, setDice] = useState<Dice[]>(
        Array.from({ length: diceCount }, (_, i) => ({
            value: dices[i] ?? null,
            held: false
        }))
    );


    const [rolling, setRolling] = useState(false);

    const rollsLeft = MAX_THROWS - throwCount

    useEffect(() => {
        // eslint-disable-next-line react-hooks/set-state-in-effect
        setDice(
            Array.from({ length: diceCount }, (_, i) => ({
                value: dices[i] ?? null,
                held: false
            }))
        );
    }, [dices, diceCount]);

    const toggleHold = (index: number, value: number | null) => {
        if (rolling || !value) return;

        setDice(prev =>
            prev.map((d, i) =>
                i === index ? { ...d, held: !d.held } : d
            )
        );

    };

    const rollDice = async () => {
        if (type === DUEL_GAME && activePlayer.userId !== me.userId) return;

        if (rolling || rollsLeft <= 0) return;

        setRolling(true);

        const heldPositions = dice.map(d => d.held);

        const throwPromise = type === DUEL_GAME
            ? ThrowService.throwDiceDuelGame(gameParticipantId ?? id, heldPositions)
            : ThrowService.throwDiceGame(gameParticipantId ?? id, heldPositions);

        await throwPromise
            .then((res) => {
                setTimeout(() => {
                    const rawValues = [
                        res['dices'].first,
                        res['dices'].second,
                        res['dices'].third,
                        res['dices'].fourth,
                        res['dices'].five,
                        res['dices'].six
                    ];

                    const values = rawValues.slice(0, diceCount);

                    setDice(prev =>
                        values.map((value, i) => ({
                            value,
                            held: prev[i]?.held ?? false
                        }))
                    );

                    if (typeof res['throwNumber'] === "number") {
                        setThrowCount(res.throwNumber ?? 0);
                    }

                    setRolling(false);
                }, 900);
            })
            .catch((err) => {
                setRolling(false);
                return;
            });
    };

    return (
        <div className={styles.wrapper}>
            <div className={styles.info}>
                {t("game.throws")}: {rollsLeft}/{MAX_THROWS}
            </div>

            <div className={styles.diceRow}>
                {dice.map((die, i) => (
                    <div
                        key={i}
                        className={`
                            ${styles.die}
                            ${die.held ? styles.held : ""}
                            ${rolling && !die.held ? styles.rolling : ""}
                        `}
                        onClick={() => toggleHold(i, die.value)}
                    >
                        {die.value ?? "-"}
                    </div>
                ))}
            </div>

            {(type === SINGLE_GAME || (type === DUEL_GAME && activePlayer.userId === me.userId)) && (
                <div className="fullSize">
                    <button
                        className="btn btn-primary"
                        onClick={rollDice}
                        disabled={rolling || rollsLeft <= 0}
                    >
                        {t("game.throwDice")}
                    </button>
                </div>
            )}
        </div>
    );
}
