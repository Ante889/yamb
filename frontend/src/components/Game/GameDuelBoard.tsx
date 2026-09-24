import {DiceRoller} from "./DiceRoller.tsx";
import ConfirmExitModal from "../Modal/ConfirmExitModal.tsx";
import {useEffect, useState} from "react";
import {useTranslation} from "react-i18next";
import {SoloGameService} from "../../services/game/soloGame/soloGameService.tsx";
import {ScoreEntryService} from "../../services/game/scoreEntry/scoreEntryService.tsx";
import {GameBoardService} from "../../services/game/gameBoard/gameBoardService.tsx";
import {useNavigate, useParams} from "react-router-dom";
import {LOWER_SECTION_ROWS, MIN_MAX_ROWS, UPPER_SECTION_ROWS} from "../../config/GameRowsConfig.tsx";
import {FINISHED_GAME_STATUS} from "../../constants/GameStatus.tsx";
import {TokenService} from "../../api/token.tsx";
import Chat from "../Chat/Chat.tsx";
import {DUEL_GAME} from "../../constants/GamePlayerTypes.tsx";
import {useGameDuelBoardEffects} from "../../hooks/game/useGameDuelBoardEffects.tsx";
import DuelGameFinishedModal from "../Modal/DuelGameFinishedModal.tsx";
import PlayerModal from "../Modal/PlayerModal.tsx";

type GameColumnDefinition = {
    label: string;
    gridKey: string;
};

type ParticipantGameData = {
    userId: number;
    participantId: number;
    username: string;
    moveStatus: string;
    grid: any;
    upperTotals: any;
    minMaxTotals: any;
    downTotals: any;
    grandTotals: any;
    diceNumber: number;
    dices: any[];
    throwNumber: number;
    announcement: string | null;
    status: string;
};

type GameBoardProps = {
    gameData: ParticipantGameData[];
    columns: GameColumnDefinition[];
    styles: Record<string, string>;
    finishedModal: boolean;
    setFinishedModal: any;
};

export function GameDuelBoard({
                                  gameData,
                                  columns,
                                  styles,
                                  finishedModal,
                                  setFinishedModal
                              }: GameBoardProps) {
    const {t} = useTranslation();
    const navigate = useNavigate();

    const [showExit, setShowExit] = useState(false);
    const [switchingTurn, setSwitchingTurn] = useState(false);
    const [localGameData, setLocalGameData] = useState(gameData);
    const [playerModal, setPlayerModal] = useState(false);
    const [clickedPlayer, setClickedPlayer] = useState(null);

    const activePlayer = localGameData?.find(
        (player) => player.moveStatus === "ACTIVE_TURN"
    );

    const me = localGameData?.find(
        (player) => player.username === TokenService.getUsername()
    );

    const otherPlayer = localGameData?.find(
        (player) => player.username !== TokenService.getUsername()
    );
    const {id} = useParams();

    const participantId = activePlayer?.participantId ?? me?.participantId;
    const grid = activePlayer?.grid ?? me?.grid;
    const upperTotals = activePlayer?.upperTotals ?? me?.upperTotals;
    const minMaxTotals = activePlayer?.minMaxTotals ?? me?.minMaxTotals;
    const downTotals = activePlayer?.downTotals ?? me?.downTotals;
    const grandTotals = activePlayer?.grandTotals ?? me?.grandTotals;
    const [throwCount, setThrowCount] = useState(
        activePlayer?.throwNumber ?? me?.throwNumber
    );

    const [announcedColumn, setAnnouncedColumn] = useState(
        activePlayer?.announcement ?? me?.announcement
    );

    useGameDuelBoardEffects(
        { gameId: id, gameData, otherPlayer, me, setLocalGameData, setThrowCount, setSwitchingTurn, setAnnouncedColumn }
    );

    const totalPoints = Object.values(grandTotals ?? {}).reduce(
        (sum: number, value: any) => sum + (value ?? 0),
        0
    );

    const handleCellClick = async (
        row: string,
        column: string
    ) => {

        if(activePlayer?.userId !== me?.userId) return;

        if (column === 'announcement' && row !== announcedColumn) {
            await handleAnnouncementClick(row);
            return;
        }

        const response =
            await ScoreEntryService.newScoreDuelEntryByParticipant(
                id,
                me?.participantId,
                row,
                column
            );

        if (!response) return;

        setLocalGameData((prev: any[]) =>
            prev.map((player) =>
                player.participantId === activePlayer.participantId
                    ? GameBoardService.applyScoreDuel(player, row, column, response.point)
                    : player
            )
        );

        setAnnouncedColumn(null);
        setThrowCount(0);

        if (response.gameStatus === FINISHED_GAME_STATUS) {
            setFinishedModal(true);
            return;
        }

        setSwitchingTurn(true);

        setTimeout(() => {
            setLocalGameData((prev: any[]) =>
                prev.map((player) => {
                    if (player.participantId === activePlayer.participantId) {
                        return { ...player, moveStatus: "WAITING_TURN" };
                    }
                    if (player.participantId === otherPlayer.participantId) {
                        return { ...player, moveStatus: "ACTIVE_TURN" };
                    }
                    return player;
                })
            );

            setSwitchingTurn(false);
        }, 1500);
    };

    const handleAnnouncementClick = async (
        row: string
    ) => {
        const response =
            await ScoreEntryService.setAnnouncement(
                participantId,
                row
            );

        if (!response) return;

        setAnnouncedColumn(response.row)
    };


    const exitGame = async () => {
        navigate("/");
    };

    const renderRow = (rowName: string, index: number) => (
        <div
            className={styles.row}
            key={rowName + '_' + index}
        >
            <div className={`${styles.cell} ${styles.cat}`}>
                {rowName}
            </div>

            {columns.map((columnDefinition) => (
                <div
                    key={columnDefinition.gridKey + '_' + index}
                    className={`
                        ${styles.cell}
                            ${
                        announcedColumn === rowName && columnDefinition.gridKey === 'announcement'
                            ? styles.announcedCell
                            : ""
                    }
                        `}
                    onClick={() =>
                        handleCellClick(
                            rowName,
                            columnDefinition.gridKey
                        )
                    }
                >
                    {
                        grid?.[rowName]?.[
                            columnDefinition.gridKey
                            ] ?? "-"
                    }
                </div>
            ))}
        </div>
    );

    return (
        <div className={styles.tableWrapper}>
            {switchingTurn && (
                <div className={styles.turnOverlay}>
                    <div className={styles.turnCard}>
                        <div className={styles.turnSpinner} />
                        <span className={styles.turnText}>
                            {t("game.waitingForPlayer", { username: otherPlayer?.username })}
                        </span>
                    </div>
                </div>
            )}
            <div className={styles.sheet}>
                <div className={styles.row}>
                    <div
                        className={`${styles.cell} ${styles.cat}`}
                    />

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`
                                ${styles.cell}
                                ${styles.rule}
                            `}
                        >
                            {columnDefinition.label}
                        </div>
                    ))}
                </div>

                {UPPER_SECTION_ROWS.map(renderRow)}

                <div className={styles.row}>
                    <div
                        className={`${styles.cell} ${styles.cat}`}
                    >
                        {t('game.sum')}
                    </div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {
                                upperTotals?.[
                                    columnDefinition.gridKey
                                    ]?.upper_sum ?? 0
                            }
                        </div>
                    ))}
                </div>

                {MIN_MAX_ROWS.map(renderRow)}

                <div className={styles.row}>
                    <div
                        className={`${styles.cell} ${styles.cat}`}
                    >
                        {t('game.sum')}
                    </div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {
                                minMaxTotals?.[
                                    columnDefinition.gridKey
                                    ]?.min_max_total ?? 0
                            }
                        </div>
                    ))}
                </div>

                {LOWER_SECTION_ROWS.map(renderRow)}

                <div className={styles.row}>
                    <div
                        className={`${styles.cell} ${styles.cat}`}
                    >
                        {t('game.sum')}
                    </div>

                    {columns.map((columnDefinition) => (
                        <div
                            key={columnDefinition.gridKey}
                            className={`${styles.cell} ${styles.totalCell}`}
                        >
                            {
                                downTotals?.[
                                    columnDefinition.gridKey
                                    ]?.down_sum ?? 0
                            }
                        </div>
                    ))}
                </div>

                <div className={styles.row}>
                    <div
                        className={`${styles.cell} ${styles.cat}`}
                    >
                        {t('game.total')}
                    </div>

                    <div
                        key={'total'}
                        className={`${styles.cell} ${styles.grandCell}`}
                    >
                        {totalPoints}
                    </div>
                </div>
                <button
                    className="btn btn-error"
                    onClick={() => setShowExit(true)}
                >
                    {t("game.exit")}
                </button>

            </div>

            <ConfirmExitModal
                open={showExit}
                onClose={() => setShowExit(false)}
                onConfirm={() => {
                    setShowExit(false);
                    exitGame();
                }}
            />

            <DuelGameFinishedModal
                isModalOpen={finishedModal}
                me={me}
                otherPlayer={otherPlayer}
            />

            <div className={styles.panel}>
                <div
                    className={styles.playerTitle}
                    style={{cursor: 'pointer'}}
                    onClick={() => {
                        setPlayerModal(true)
                        setClickedPlayer(activePlayer)
                    }}
                >
                    <span className={styles.username}>
                        {activePlayer?.username ?? me?.username}
                    </span>
                </div>
                <DiceRoller
                    diceCount={activePlayer?.diceNumber ?? 6}
                    dices={activePlayer?.dices ?? []}
                    throwCount={throwCount}
                    setThrowCount={setThrowCount}
                    type={DUEL_GAME}
                    gameParticipantId={participantId}
                    me={me}
                    activePlayer={activePlayer ?? me}
                />
                <Chat
                    username={activePlayer?.username}
                    gameId={id}
                    gameParticipantId={me.participantId}
                />

                <PlayerModal
                    isModalOpen={playerModal}
                    onClose={() => {
                        setPlayerModal(false)
                        setClickedPlayer(null)
                    }}
                    activePlayer = {activePlayer}
                />
            </div>
        </div>
    );
}
