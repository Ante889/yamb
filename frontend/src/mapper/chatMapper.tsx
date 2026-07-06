export function applyEventForChat(
    prevMessages: any[],
    action: string,
    incoming: any
): any[] {
    if (action === "created") {
        return [...prevMessages, incoming];
    }

    return prevMessages;
}
