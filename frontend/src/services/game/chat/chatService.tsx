import {getChatByGameIdApi, setChatByGameParticipantIdApi} from "../../../api/game/chatApi.tsx";

export const ChatService = {
    getChatByGame: async (gameId) => {
        return await getChatByGameIdApi(gameId);
    },
    setChatByGameParticipant: async (gameParticipantId, text) => {
        return await setChatByGameParticipantIdApi(gameParticipantId, {text: text.trim()});
    }
};
