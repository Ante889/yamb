import {GameBoard} from "../GameBoard";
import styles from "../SoloGame.module.css";

export function ClassicGame({gameData, styles}: any) {
    return (
        <GameBoard
            gameData={gameData}
            styles={styles}
            columns={[
                {label: "↓", gridKey: "down"},
                {label: "↑", gridKey: "up"},
                {label: "↕", gridKey: "free"},
                {label: "N", gridKey: "announcement"},
            ]}
        />
    );
}
