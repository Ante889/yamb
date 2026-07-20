<?php

namespace App\Shared\Domain\Mercure;

final class MercureTopics
{
    public static function rooms(): string
    {
        return '/rooms';
    }

    public static function gameChat(int $gameId): string
    {
        return '/game/' . $gameId;
    }

    public static function startedGame(int $gameId): string
    {
        return '/started-game/' . $gameId;
    }

    public static function gameParticipantsPattern(): string
    {
        return '/game-participants/{id}';
    }
}
