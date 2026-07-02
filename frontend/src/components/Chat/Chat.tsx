import { useState, useRef, useEffect } from "react";
import styles from "./Chat.module.css";
import {useTranslation} from "react-i18next";

type Message = {
    id: string;
    user: string;
    text: string;
    createdAt: number;
};

interface Props {
    username: string;
    gameId: string
}

function Chat({ username, gameId }: Props) {
    const {t} = useTranslation();
    const [messages, setMessages] = useState<Message[]>([]);
    const [text, setText] = useState("");

    const bottomRef = useRef<HTMLDivElement | null>(null);

    const sendMessage = () => {
        if (!text.trim()) return;

        const newMessage: Message = {
            id: crypto.randomUUID(),
            user: username,
            text: text.trim(),
            createdAt: Date.now(),
        };

        setMessages((prev) => [...prev, newMessage]);
        setText("");
    };

    useEffect(() => {
        bottomRef.current?.scrollIntoView({ behavior: "smooth" });
    }, [messages]);

    const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === "Enter") sendMessage();
    };

    return (
        <div className={styles.chat}>
            <div className={styles.header}>Chat</div>

            <div className={styles.messages}>
                {messages.map((m) => (
                    <div
                        key={m.id}
                        className={`${styles.message} ${
                            m.user === username ? styles.me : ""
                        }`}
                    >
                        <div className={styles.meta}>
                            <span>{m.user}</span>
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
                    placeholder= {t('chat.typeAMessage')}
                />

                <button className="btn-primary" onClick={sendMessage}>{t('chat.send')}</button>
            </div>
        </div>
    );
}

export default Chat;
