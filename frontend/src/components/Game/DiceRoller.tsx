import { useState } from "react";
import styles from "./DiceRoller.module.css";

type Dice = {
    value: number;
    held: boolean;
};

type Props = {
    diceCount?: number;
    onRollComplete?: (values: number[]) => void;
};

export function DiceRoller({
                               diceCount = 5,
                               onRollComplete
                           }: Props) {
    const createDice = (): Dice[] =>
        Array.from({ length: diceCount }, () => ({
            value: 1,
            held: false
        }));

    const [dice, setDice] = useState<Dice[]>(createDice());
    const [rollsLeft, setRollsLeft] = useState(3);
    const [rolling, setRolling] = useState(false);

    const toggleHold = (index: number) => {
        if (rolling) return;

        setDice(prev =>
            prev.map((d, i) =>
                i === index ? { ...d, held: !d.held } : d
            )
        );
    };

    const rollDice = () => {
        if (rolling || rollsLeft === 0) return;

        setRolling(true);

        setTimeout(() => {
            const updated = dice.map(d =>
                d.held
                    ? d
                    : {
                        ...d,
                        value: Math.floor(Math.random() * 6) + 1
                    }
            );

            setDice(updated);
            setRollsLeft(prev => prev - 1);
            setRolling(false);

            onRollComplete?.(updated.map(d => d.value));
        }, 900);
    };

    const resetTurn = () => {
        setDice(createDice());
        setRollsLeft(3);
    };

    return (
        <div className={styles.wrapper}>
            <div className={styles.info}>
                Bacanja: {rollsLeft}/3
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
                        onClick={() => toggleHold(i)}
                    >
                        {die.value}
                    </div>
                ))}
            </div>

            <div className={'fullSize '}>
                <button
                    className={'btn btn-primary'}
                    onClick={rollDice}
                    disabled={rolling || rollsLeft === 0}
                >
                    Baci kockice
                </button>
            </div>
        </div>
    );
}
