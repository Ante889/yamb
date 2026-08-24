import { mercureApi } from "../../api/mercure/mercureApi.tsx";
import { BASE_MERCURE_URL } from "../../config/AppConfig.tsx";

export const MercureService = {
    async getMercureToken(gameId?: number) {
        return await mercureApi(gameId);
    },

    async subscribeToGameStarted(callback: (data: any) => void): Promise<EventSource> {
        const { token } = await this.getMercureToken();

        const url = new URL(BASE_MERCURE_URL);
        url.searchParams.append("topic", "/started-game");
        url.searchParams.append("authorization", token);

        const es = new EventSource(url);

        es.onmessage = (event) => callback(JSON.parse(event.data));

        return es;
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

    async subscribeToDuelGameDice(
        gameId: number,
        callback: (data: any) => void
    ): Promise<EventSource> {

        const { token } = await this.getMercureToken(gameId);

        const url = new URL(BASE_MERCURE_URL);

        url.searchParams.append("topic", `/game-participant-dice/${gameId}`);
        url.searchParams.append("authorization", token);

        const es = new EventSource(url);

        es.onmessage = (event) => callback(JSON.parse(event.data));

        return es;
    },

    async subscribeToDuelGameTurn(
        gameId: number,
        callback: (data: any) => void
    ): Promise<EventSource> {

        const { token } = await this.getMercureToken(gameId);

        const url = new URL(BASE_MERCURE_URL);

        url.searchParams.append("topic", `/game-participant-turn/${gameId}`);
        url.searchParams.append("authorization", token);

        const es = new EventSource(url);

        es.onmessage = (event) => callback(JSON.parse(event.data));

        return es;
    },

    async subscribeToAnnouncement(
        gameParticipant: number,
        callback: (data: any) => void
    ): Promise<EventSource> {

        const { token } = await this.getMercureToken(gameParticipant);

        const url = new URL(BASE_MERCURE_URL);

        url.searchParams.append("topic", `/set-announcement-field/${gameParticipant}`);
        url.searchParams.append("authorization", token);

        const es = new EventSource(url);

        es.onmessage = (event) => callback(JSON.parse(event.data));

        return es;
    },
};
