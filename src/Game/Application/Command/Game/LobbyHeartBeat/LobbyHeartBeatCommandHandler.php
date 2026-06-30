<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game\LobbyHeartBeat;

use App\Shared\Domain\Cache\RedisCacheServiceInterface;
use App\Shared\Infrastructure\Cache\CacheKeyGenerator;
use Psr\Cache\InvalidArgumentException;

final class LobbyHeartBeatCommandHandler
{
    private int $ttlSeconds = 20;

    public function __construct(
        private readonly RedisCacheServiceInterface         $redisCacheService,
        private readonly CacheKeyGenerator                  $cacheKeyGenerator
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle(LobbyHeartBeatCommand $command): void
    {
        if ($command->gameParticipantId) {
            $this->redisCacheService->set(
                $this->cacheKeyGenerator->forHeartBeat($command->gameParticipantId),
                true,
                $this->ttlSeconds
            );
        }
    }
}
