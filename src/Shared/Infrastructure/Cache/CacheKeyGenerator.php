<?php

namespace App\Shared\Infrastructure\Cache;

use App\Shared\Domain\Cache\RedisCacheServiceInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

{
final class CacheKeyGenerator
{
    public function forTurn(int $gameParticipantId): string
    {
        return sprintf('yamb:game-participant:%d:turn', $gameParticipantId);
    }
}
}
