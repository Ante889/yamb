function getRandomDice() {
    return Math.floor(Math.random() * 6) + 1;
}

function fakeBackendRequest(username: string, password: string) {
    return new Promise<boolean>((resolve) => {
        setTimeout(() => {
            const success = Math.random() < 0.3;
            resolve(success);
        }, 900);
    });
}

export async function rollDiceLogin(username: string, password: string) {
    const success = await fakeBackendRequest(username, password);

    let result: number[];

    if (success) {
        result = Array(6).fill(6);
    } else {
        do {
            result = Array.from({ length: 6 }, getRandomDice);
        } while (result.every(n => n === 6));
    }

    return result;
}
