import {GameBoard} from "../GameBoard";

export function ClassicGame({gameData, styles, participantId, finishedModal, setFinishedModal}: any) {
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
            participantId={participantId}
            finishedModal={finishedModal}
            setFinishedModal={setFinishedModal}
        />
    );
}
