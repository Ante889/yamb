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
    gameId: any;
    gameParticipantId: any;
}


function Chat({ username, gameId, gameParticipantId }: Props) {
    const { t } = useTranslation();
    const [messages, setMessages] = useState<Message[]>([]);
    const [text, setText] = useState("");
    const esRef = useRef<EventSource | null>(null);
    const bottomRef = useRef<HTMLDivElement | null>(null);
    const audioCtxRef = useRef<AudioContext | null>(null);

    const getAudioContext = () => {
        if (!audioCtxRef.current) {
            audioCtxRef.current = new (window.AudioContext ||
                (window as any).webkitAudioContext)();
        }
        return audioCtxRef.current;
    };

    const playNotificationSound = () => {
        try {
            const ctx = getAudioContext();

            if (ctx.state === "suspended") {
                ctx.resume();
            }

            const oscillator = ctx.createOscillator();
            const gain = ctx.createGain();

            oscillator.type = "sine";
            oscillator.frequency.setValueAtTime(880, ctx.currentTime); // A5 ton
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.3);

            oscillator.connect(gain);
            gain.connect(ctx.destination);

            oscillator.start();
            oscillator.stop(ctx.currentTime + 0.3);
        } catch (error) {
            console.error("Ne mogu odsvirati zvuk:", error);
        }
    };

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

                    if (
                        data.action === "created" &&
                        data.payload?.username &&
                        data.payload.username !== username
                    ) {
                        playNotificationSound();
                    }

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
    }, [gameId, username]);

    useEffect(() => {
        bottomRef.current?.scrollIntoView({ behavior: "smooth" });
    }, [messages]);

    const sendMessage = async () => {
        if (!text.trim()) return;

        getAudioContext();

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
                    onFocus={getAudioContext}
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