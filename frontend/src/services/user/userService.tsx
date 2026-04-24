import { TokenService } from "../../api/token";
import {getCurrentUserData} from "../../api/user/userApi.tsx";

export const UserService = {
    getCurrentUserData: async () => {
        const data = await getCurrentUserData();

        const { username, email } = data;

        TokenService.setUserData(
            username,
            email
        );

        return data;
    },
};
