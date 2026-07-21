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

    public static function startedGame(): string
    {
        return '/started-game';
    }

    public static function gameParticipantsPattern(): string
    {
        return '/game-participants/{id}';
    }
}
