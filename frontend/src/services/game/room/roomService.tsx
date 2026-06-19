import { createRoomApi } from "../../../api/game/roomApi.tsx";

export const RoomService = {
    createRoom: async (diceCount: number, gameType: string, isPublic: boolean) => {
        return await createRoomApi(diceCount, gameType, isPublic);
    },
};
