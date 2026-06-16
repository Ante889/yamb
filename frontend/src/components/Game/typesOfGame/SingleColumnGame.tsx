import { GameBoard } from "../GameBoard";

export function SingleColumnGame({ gameData, styles, participantId, finishedModal, setFinishedModal}: any) {
    return (
        <GameBoard
            gameData={gameData}
            styles={styles}
            columns={[
                {label: "↕", gridKey: "free"},
            ]}
            participantId={participantId}
            finishedModal={finishedModal}
            setFinishedModal={setFinishedModal}
        />
    );
}
