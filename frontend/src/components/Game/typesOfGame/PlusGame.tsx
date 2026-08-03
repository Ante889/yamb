import { GameBoard } from "../GameBoard";
import {DUEL_GAME, SINGLE_GAME} from "../../../constants/GamePlayerTypes.tsx";
import {GameDuelBoard} from "../GameDuelBoard.tsx";

export function PlusGame({ gameData, styles, participantId, finishedModal, setFinishedModal, type }: any) {
    if(type === DUEL_GAME) {
        return (
            <GameDuelBoard
                gameData={gameData}
                styles={styles}
                columns={[
                    {label: "↓", gridKey: "down"},
                    {label: "↑", gridKey: "up"},
                    {label: "↕", gridKey: "free"},
                    {label: "N", gridKey: "announcement"},
                    { label: "🖐️", gridKey: "hand" }
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
                    {label: "↓", gridKey: "down"},
                    {label: "↑", gridKey: "up"},
                    {label: "↕", gridKey: "free"},
                    {label: "N", gridKey: "announcement"},
                    { label: "🖐️", gridKey: "hand" }
                ]}
                participantId={participantId}
                finishedModal={finishedModal}
                setFinishedModal={setFinishedModal}
            />
        );
    }
}
