<?php

namespace App\Shared\Infrastructure\Cache;

final class CacheKeyGenerator
{
    public function forTurn(int $gameParticipantId): string
    {
        return sprintf('yamb:game-participant:%d:turn', $gameParticipantId);
    }
}
