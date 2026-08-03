import { GameBoard } from "../GameBoard";
import {DUEL_GAME, SINGLE_GAME} from "../../../constants/GamePlayerTypes.tsx";
import {GameDuelBoard} from "../GameDuelBoard.tsx";

export function SingleColumnGame({ gameData, styles, participantId, finishedModal, setFinishedModal, type}: any) {
    if(type === DUEL_GAME) {
        return (
            <GameDuelBoard
                gameData={gameData}
                styles={styles}
                columns={[
                    {label: "↕", gridKey: "free"},
                ]}
                finishedModal={finishedModal}
                setFinishedModal={setFinishedModal}
            />
        );
    } else if (type === SINGLE_GAME) {
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
}
