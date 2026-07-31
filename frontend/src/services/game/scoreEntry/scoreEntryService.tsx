import {newEntryApi, scoreDuelEntryApi, scoreEntryApi, setAnnouncementApi} from "../../../api/game/scoreEntryApi.tsx";

export const ScoreEntryService = {
    getScoreEntryByParticipant: async (participantId) => {
        return await scoreEntryApi(participantId);
    },
    getScoreDuelEntryByGame: async (gameId) => {
        return await scoreDuelEntryApi(gameId);
    },
    newScoreEntryByParticipant: async (participantId, row, column) => {
        return await newEntryApi(participantId, {
            row: row,
            column: column
        });
    },
    setAnnouncement: async (participantId, row) => {
        return await setAnnouncementApi(participantId, {
            row: row,
        });
    }
};
