<?php

namespace App\Shared\Domain\Mercure;

use App\Game\Domain\Model\Game;

final class MercureTopics
{
    public static function rooms(Game $game): string
    {
        return '/rooms/' . $game->getId();
    }

    public static function gameParticipants(Game $game): string
    {
        return '/game-participants/' . $game->getId();
    }
}
