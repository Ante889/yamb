import {scoreEntryApi} from "../../../api/game/scoreEntryApi.tsx";

export const ScoreEntryService = {
    getScoreEntryByParticipant: async (participantId) => {
        return await scoreEntryApi(participantId);
    },
};
