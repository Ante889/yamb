import { GameBoard } from "../GameBoard";

export function SingleColumnGame({ gameData, styles }: any) {
    return (
        <GameBoard
            gameData={gameData}
            styles={styles}
            columns={[
                {label: "↕", gridKey: "free"},
            ]}
        />
    );
}
