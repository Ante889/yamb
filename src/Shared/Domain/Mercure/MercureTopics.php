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

    public static function gameParticipantTurn(int $gameParticipantId): string
    {
        return '/game-participant-turn/' . $gameParticipantId;
    }

    public static function gameParticipantAnnouncementField(int $gameParticipantId): string
    {
        return '/set-announcement-field/' . $gameParticipantId;
    }

    public static function gameParticipantDice(int $gameParticipantId): string
    {
        return '/game-participant-dice/' . $gameParticipantId;
    }

    public static function startedGame(): string
    {
        return '/started-game';
    }

    public static function finishedGame(int $gameId): string
    {
        return '/finished-game/' . $gameId;
    }

    public static function gameParticipantsPattern(): string
    {
        return '/game-participants/{id}';
    }
}
