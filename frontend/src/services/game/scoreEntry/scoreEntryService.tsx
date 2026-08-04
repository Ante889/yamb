import {
    newDuelEntryApi,
    newEntryApi,
    scoreDuelEntryApi,
    scoreEntryApi,
    setAnnouncementApi
} from "../../../api/game/scoreEntryApi.tsx";

export const ScoreEntryService = {
    getScoreEntryByParticipant: async (participantId: any) => {
        return await scoreEntryApi(participantId);
    },
    getScoreDuelEntryByGame: async (gameId: any) => {
        return await scoreDuelEntryApi(gameId);
    },
    newScoreEntryByParticipant: async (participantId: any, row: any, column: any) => {
        return await newEntryApi(participantId, {
            row: row,
            column: column
        });
    },
    setAnnouncement: async (participantId: any, row: any) => {
        return await setAnnouncementApi(participantId, {
            row: row,
        });
    },
    newScoreDuelEntryByParticipant: async (gameId: any, participantId: any, row: any, column: any) => {
        return await newDuelEntryApi(gameId, participantId, {
            row: row,
            column: column
        });
    },
};
