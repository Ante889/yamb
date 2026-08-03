import {useParams} from "react-router-dom";
import {useEffect, useState} from "react";
import {ScoreEntryService} from "../../../services/game/scoreEntry/scoreEntryService.tsx";
import {FINISHED_GAME_STATUS} from "../../../constants/GameStatus.tsx";
import {JambLoading} from "../../../components/Loading/JambLoading.tsx";
import {CLASSIC_GAME_TYPE, SINGLE_COLUMN_GAME_TYPE, YAMB_PLUS_GAME_TYPE} from "../../../constants/GameType.tsx";
import {SingleColumnGame} from "../../../components/Game/typesOfGame/SingleColumnGame.tsx";
import {ClassicGame} from "../../../components/Game/typesOfGame/ClassicGame.tsx";
import {PlusGame} from "../../../components/Game/typesOfGame/PlusGame.tsx";
import styles  from "../../game/SoloGame.module.css";
import {DUEL_GAME} from "../../../constants/GamePlayerTypes.tsx";

function DuelGame() {
    const { id } = useParams();
    const [gameData, setGameData] = useState<any[] | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [finishedModal, setFinishedModal] = useState(false);

    useEffect(() => {
        const loadGameData = async () => {
            if (!id) return;

            setIsLoading(true);

            try {
                const response = await ScoreEntryService.getScoreDuelEntryByGame(Number(id));
                setGameData(response);

                if (Array.isArray(response) && response.some((p: any) => p.status === FINISHED_GAME_STATUS)) {
                    setFinishedModal(true);
                }
            } finally {
                setIsLoading(false);
            }
        };

        loadGameData();
    }, [id]);

    if (isLoading) return <JambLoading />;

    if (!gameData || !Array.isArray(gameData) || gameData.length === 0) {
        return null;
    }

    const gameType = gameData[0].gameType;

    switch (gameType) {
        case SINGLE_COLUMN_GAME_TYPE:
            return (
                <>
                    <SingleColumnGame
                        gameData={gameData}
                        styles={styles}
                        participantId={null}
                        finishedModal={finishedModal}
                        setFinishedModal={setFinishedModal}
                        type={DUEL_GAME}
                    />
                </>
            );

        case CLASSIC_GAME_TYPE:
            return (
                <>
                    <ClassicGame
                        gameData={gameData}
                        styles={styles}
                        participantId={null}
                        finishedModal={finishedModal}
                        setFinishedModal={setFinishedModal}
                        type={DUEL_GAME}
                    />
                </>
            );

        case YAMB_PLUS_GAME_TYPE:
            return (
                <>
                    <PlusGame
                        gameData={gameData}
                        styles={styles}
                        participantId={null}
                        finishedModal={finishedModal}
                        setFinishedModal={setFinishedModal}
                        type={DUEL_GAME}
                    />
                </>
            );

        default:
            return null;
    }
}

export default DuelGame;
