import {mercureApi} from "../../api/mercure/mercureApi.tsx";

export const MercureService = {
    getMercureToken: async () => {
        return await mercureApi();
    }
};
