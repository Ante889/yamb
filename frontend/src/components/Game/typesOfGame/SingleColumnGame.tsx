import { GameBoard } from "../GameBoard";

export function SingleColumnGame({ gameData, styles, participantId }: any) {
    return (
        <GameBoard
            gameData={gameData}
            styles={styles}
            columns={[
                {label: "↕", gridKey: "free"},
            ]}
            participantId={participantId}
        />
    );
}
