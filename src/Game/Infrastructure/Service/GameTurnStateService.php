<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Service;

use App\Shared\Domain\Cache\RedisCacheServiceInterface;
use App\Shared\Infrastructure\Cache\CacheKeyGenerator;
use Psr\Cache\InvalidArgumentException;

final class GameTurnStateService
{
    private int $ttlSeconds = 86400;

    public function __construct(
        private readonly CacheKeyGenerator $cacheKeyGenerator,
        private readonly RedisCacheServiceInterface $redisCacheService
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getForParticipant(int $participantId): ?array
    {
        return $this->redisCacheService->get(
            $this->cacheKeyGenerator->forTurn($participantId)
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setForParticipant(int $participantId, mixed $data): void
    {
        $this->redisCacheService->set(
            $this->cacheKeyGenerator->forTurn($participantId),
            $data,
            $this->ttlSeconds
        );
    }

    public function deleteParticipant(int $participantId): void
    {
        $this->redisCacheService->delete(
            $this->cacheKeyGenerator->forTurn($participantId)
        );
    }
}
