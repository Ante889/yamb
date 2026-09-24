import { TokenService } from "../../api/token";
import {getCurrentUserData, getUserStatistic} from "../../api/user/userApi.tsx";

export const UserService = {
    getCurrentUserData: async () => {
        const data = await getCurrentUserData();

        const { username, email, key } = data;
        TokenService.setUserData(
            username,
            email,
            key
        );

        return data;
    },
    getStatistics: async (id) => {
        return await getUserStatistic(id);
    }
};
