import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { ScoreEntryService } from "../../services/game/scoreEntry/scoreEntryService";
import { JambLoading } from "../../components/Loading/JambLoading";
import { SingleColumnGame } from "./typesOfGame/SingleColumnGame";
import { ClassicGame } from "./typesOfGame/ClassicGame";
import { PlusGame } from "./typesOfGame/PlusGame";
import styles  from "./SoloGame.module.css";

export default function SoloGame() {
    const { id } = useParams();
    const [gameData, setGameData] = useState<any>(null);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        const loadGameData = async () => {
            if (!id) return;

            setIsLoading(true);

            try {
                const response =
                    await ScoreEntryService.getScoreEntryByParticipant(Number(id));

                setGameData(response);
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
        case "SINGLE_COLUMN":
            return <SingleColumnGame gameData={gameData} styles={styles} />;

        case "CLASSIC":
            return <ClassicGame gameData={gameData} styles={styles} />;

        case "YAMB_PLUS":
            return <PlusGame gameData={gameData} styles={styles} />;

        default:
            return null;
    }
}
