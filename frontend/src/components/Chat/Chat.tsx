import {useEffect, useRef, useState} from "react";
import styles from "./Chat.module.css";
import {useTranslation} from "react-i18next";
import {ChatService} from "../../services/game/chat/chatService.tsx";
import {MercureService} from "../../services/mercure/mercureService.tsx";
import {applyEventForChat} from "../../mapper/chatMapper";

type Message = {
    id: string;
    username: string;
    text: string;
    createdAt: number;
};

interface Props {
    username: string;
    gameId: string;
    gameParticipantId: string;
}

function Chat({ username, gameId, gameParticipantId }: Props) {
    const { t } = useTranslation();
    const [messages, setMessages] = useState<Message[]>([]);
    const [text, setText] = useState("");
    const esRef = useRef<EventSource | null>(null);
    const bottomRef = useRef<HTMLDivElement | null>(null);

    useEffect(() => {
        let ignore = false;

        const load = async () => {
            try {
                const response = await ChatService.getChatByGame(gameId);

                if (!ignore) {
                    setMessages(response);
                }
            } catch (error) {
                console.error(error);
            }
        };

        load();

        return () => {
            ignore = true;
        };
    }, [gameId]);

    useEffect(() => {
        let mounted = true;

        const connect = async () => {
            const es = await MercureService.subscribeToGameChat(
                Number(gameId),
                (data) => {
                    if (!mounted) return;

                    setMessages((prev) =>
                        applyEventForChat(prev, data.action, data.payload)
                    );
                }
            );

            if (!mounted) {
                es.close();
                return;
            }

            esRef.current = es;
        };

        connect();

        return () => {
            mounted = false;
            esRef.current?.close();
            esRef.current = null;
        };
    }, [gameId]);

    useEffect(() => {
        bottomRef.current?.scrollIntoView({ behavior: "smooth" });
    }, [messages]);

    const sendMessage = async () => {
        if (!text.trim()) return;

        try {
            await ChatService.setChatByGameParticipant(
                gameParticipantId,
                text
            );

            setText("");
        } catch (error) {
            console.error(error);
        }
    };

    const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === "Enter") {
            sendMessage();
        }
    };

    return (
        <div className={styles.chat}>
            <div className={styles.header}>{t('chat.chat')}</div>

            <div className={styles.messages}>
                {messages.map((m) => (
                    <div
                        key={m.id}
                        className={`${styles.message} ${
                            m.username === username ? styles.me : ""
                        }`}
                    >
                        <div className={styles.meta}>
                            <span>{m.username}</span>
                        </div>
                        <div className={styles.text}>{m.text}</div>
                    </div>
                ))}
                <div ref={bottomRef} />
            </div>

            <div className={styles.inputRow}>
                <input
                    value={text}
                    onChange={(e) => setText(e.target.value)}
                    onKeyDown={handleKeyDown}
                    placeholder={t("chat.typeAMessage")}
                />

                <button className="btn-primary" onClick={sendMessage}>
                    {t("chat.send")}
                </button>
            </div>
        </div>
    );
}

export default Chat;
