import {createRoomApi, getPublicRoomsApi, joinRoomWithJoinCodeApi} from "../../../api/game/roomApi.tsx";

export const RoomService = {
    getPublicRooms: async () => {
        return await getPublicRoomsApi();
    },
    createRoom: async (diceCount: number, gameType: string, isPublic: boolean) => {
        return await createRoomApi(diceCount, gameType, isPublic);
    },
    joinRoomWithJoinCode: async (joinCode: string) => {
        return await joinRoomWithJoinCodeApi(joinCode);
    }
};
