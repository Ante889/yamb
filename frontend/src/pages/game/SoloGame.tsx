import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { ScoreEntryService } from "../../services/game/scoreEntry/scoreEntryService";
import { JambLoading } from "../../components/Loading/JambLoading";
import { SingleColumnGame } from "../../components/Game/typesOfGame/SingleColumnGame";
import { ClassicGame } from "../../components/Game/typesOfGame/ClassicGame";
import { PlusGame } from "../../components/Game/typesOfGame/PlusGame";
import styles  from "./SoloGame.module.css";
import {FINISHED_GAME_STATUS} from "../../constants/GameStatus.tsx";
import {CLASSIC_GAME_TYPE, SINGLE_COLUMN_GAME_TYPE, YAMB_PLUS_GAME_TYPE} from "../../constants/GameType.tsx";

export default function SoloGame() {
    const { id } = useParams();
    const [gameData, setGameData] = useState<any>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [finishedModal, setFinishedModal] = useState(false);

    useEffect(() => {
        const loadGameData = async () => {
            if (!id) return;

            setIsLoading(true);

            try {
                const response =
                    await ScoreEntryService.getScoreEntryByParticipant(Number(id));

                setGameData(response);

                if (response['status'] === FINISHED_GAME_STATUS) {
                    setFinishedModal(true);
                }
            } finally {
                setIsLoading(false);
            }
        };

        loadGameData();
    }, [id]);

    if (isLoading) {
        return <JambLoading />;
    }

    if (!gameData) {
        return null;
    }

    switch (gameData.gameType) {
        case SINGLE_COLUMN_GAME_TYPE:
            return <SingleColumnGame
                gameData={gameData} styles={styles} participantId={id} finishedModal={finishedModal} setFinishedModal={setFinishedModal}
            />;

        case CLASSIC_GAME_TYPE:
            return <ClassicGame
                gameData={gameData} styles={styles} participantId={id} finishedModal={finishedModal} setFinishedModal={setFinishedModal}
            />;

        case YAMB_PLUS_GAME_TYPE:
            return <PlusGame
                gameData={gameData} styles={styles} participantId={id} finishedModal={finishedModal} setFinishedModal={setFinishedModal}
            />;

        default:
            return null;
    }
}
