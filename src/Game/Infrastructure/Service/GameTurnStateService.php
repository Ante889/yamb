<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Service;

use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Exception\EnterNewEntryException;
use App\Shared\Domain\Cache\RedisCacheServiceInterface;
use App\Shared\Infrastructure\Cache\CacheKeyGenerator;
use Psr\Cache\InvalidArgumentException;

final class GameTurnStateService implements TurnStateStoreInterface
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
    public function getDicesByParticipant(int $participantId): array
    {
        $state = $this->getForParticipant($participantId);

        if ($state === null) return [];

        if (!array_key_exists('dices', $state)) {
            throw EnterNewEntryException::noDices();
        }

        return $state['dices'];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getThrowNumberByParticipant(int $participantId): int
    {
        $state = $this->getForParticipant($participantId);

        if ($state === null) return 0;

        if (!array_key_exists('throwNumber', $state)) {
            throw EnterNewEntryException::noDices();
        }

        return $state['throwNumber'];
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
