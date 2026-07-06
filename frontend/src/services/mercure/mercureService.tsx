import { mercureApi } from "../../api/mercure/mercureApi.tsx";
import { BASE_MERCURE_URL } from "../../config/AppConfig.tsx";

export const MercureService = {
    async getMercureToken(gameId?: number) {
        return await mercureApi(gameId);
    },

    async subscribeToRooms(callback: (data: any) => void): Promise<EventSource> {
        const { token } = await this.getMercureToken();

        const url = new URL(BASE_MERCURE_URL);
        url.searchParams.append("topic", "/rooms");
        url.searchParams.append("authorization", token);

        const es = new EventSource(url);

        es.onmessage = (event) => callback(JSON.parse(event.data));

        return es;
    },

    async subscribeToGameChat(
        gameId: number,
        callback: (data: any) => void
    ): Promise<EventSource> {

        const { token } = await this.getMercureToken(gameId);

        const url = new URL(BASE_MERCURE_URL);

        url.searchParams.append("topic", `/game/${gameId}`);
        url.searchParams.append("authorization", token);

        const es = new EventSource(url);

        es.onmessage = (event) => callback(JSON.parse(event.data));

        return es;
    },
};
