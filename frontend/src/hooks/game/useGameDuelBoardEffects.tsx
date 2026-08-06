import { useEffect } from "react";
import { MercureService } from "../../services/mercure/mercureService.tsx";
import {GameBoardService} from "../../services/game/gameBoard/gameBoardService.tsx";

interface UseGameDuelBoardEffectsParams {
    gameId: any;
    gameData: any[];
    otherPlayer: any;
    setLocalGameData: React.Dispatch<React.SetStateAction<any[]>>;
    setThrowCount: React.Dispatch<React.SetStateAction<any>>;
    setSwitchingTurn: React.Dispatch<React.SetStateAction<any>>;
    setAnnouncedColumn: React.Dispatch<React.SetStateAction<any>>;
}

const diceObjectToArray = (dicesObj: any): number[] => [
    dicesObj?.first,
    dicesObj?.second,
    dicesObj?.third,
    dicesObj?.fourth,
    dicesObj?.five,
    dicesObj?.six,
];

export function useGameDuelBoardEffects({
                                            gameId,
                                            gameData,
                                            otherPlayer,
                                            setLocalGameData,
                                            setThrowCount,
                                            setSwitchingTurn,
                                            setAnnouncedColumn
                                        }: UseGameDuelBoardEffectsParams) {

    useEffect(() => {
        setLocalGameData(gameData);
    }, [gameData]);

    useEffect(() => {
        if (!otherPlayer?.participantId) return;

        let mounted = true;
        let es: EventSource | null = null;

        const connect = async () => {
            es = await MercureService.subscribeToDuelGameDice(
                Number(otherPlayer.participantId),
                (data: any) => {
                    if (!mounted) return;
                    if (data.action === "created" && data.payload) {
                        const dicesArray = diceObjectToArray(data.payload.dices);
                        const { throwNumber } = data.payload;

                        setLocalGameData((prev) =>
                            prev.map((player) =>
                                player.participantId === otherPlayer.participantId
                                    ? {
                                        ...player,
                                        dices: dicesArray,
                                        throwNumber: throwNumber ?? player.throwNumber,
                                    }
                                    : player
                            )
                        );

                        setThrowCount(throwNumber);
                    }
                }
            );

            if (!mounted) {
                es?.close();
                return;
            }
        };

        connect();

        return () => {
            mounted = false;
            es?.close();
        };
    }, [otherPlayer?.participantId]);

    useEffect(() => {
        if (!otherPlayer?.participantId) return;

        let mounted = true;
        let es: EventSource | null = null;

        const connect = async () => {
            es = await MercureService.subscribeToDuelGameTurn(
                Number(otherPlayer.participantId),
                (data: any) => {
                    if (!mounted) return;
                    if (data.action === "created" && data.payload) {
                        setSwitchingTurn(true);

                        setLocalGameData((prev: any[]) =>
                            prev.map((player) =>
                                player.participantId === otherPlayer.participantId
                                    ? GameBoardService.applyScoreDuel(player, data.payload.row, data.payload.column, data.payload.point)
                                    : player
                            )
                        );

                        setTimeout(() => {
                            setLocalGameData((prev: any[]) =>
                                prev.map((player) => {
                                    if (player.participantId === otherPlayer.participantId) {
                                        return { ...player, moveStatus: "WAITING_TURN" };
                                    }
                                    if (player.participantId !== otherPlayer.participantId) {
                                        return { ...player, moveStatus: "ACTIVE_TURN" };
                                    }
                                    return player;
                                })
                            );
                            setAnnouncedColumn(null);
                            setThrowCount(0);
                            setSwitchingTurn(false);
                        }, 1500);
                    }
                }
            );

            if (!mounted) {
                es?.close();
                return;
            }
        };

        connect();

        return () => {
            mounted = false;
            es?.close();
        };
    }, [otherPlayer?.participantId]);
}