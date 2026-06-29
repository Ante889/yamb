<?php

namespace App\Shared\Domain\Mercure;

final class MercureTopics
{
    public static function rooms(): string
    {
        return '/rooms';
    }

    public static function gameParticipants(): string
    {
        return '/game-participants';
    }

    public static function gameParticipantsPattern(): string
    {
        return '/game-participants/{id}';
    }
}
