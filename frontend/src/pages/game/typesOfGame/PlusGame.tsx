import { GameBoard } from "../GameBoard";

export function PlusGame({ gameData, styles }: any) {
    return (
        <GameBoard
            gameData={gameData}
            styles={styles}
            columns={[
                {label: "↓", gridKey: "down"},
                {label: "↑", gridKey: "up"},
                {label: "↕", gridKey: "free"},
                {label: "N", gridKey: "announcement"},
                { label: "🖐️", gridKey: "hand" }
            ]}
        />
    );
}
