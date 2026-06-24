<?php

namespace App\Shared\Domain\Mercure;

use App\Game\Domain\Model\Game;

final class MercureTopics
{
    public static function rooms(): string
    {
        return '/rooms';
    }

    public static function gameParticipants(Game $game): string
    {
        return '/game-participants/' . $game->getId();
    }
}
