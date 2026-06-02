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
            return [];
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
            return 0;
        }

        return $state['throwNumber'];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setForParticipant(int $participantId, mixed $data): void
    {
        $existing = $this->getForParticipant($participantId) ?? [];

        $merged = array_replace_recursive($existing, $data);

        $this->redisCacheService->set(
            $this->cacheKeyGenerator->forTurn($participantId),
            $merged,
            $this->ttlSeconds
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setAnnouncement(int $participantId, string $row): void
    {
        $state = $this->getForParticipant($participantId) ?? [];

        $state['announcement'] = $row;

        $this->setForParticipant($participantId, $state);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getAnnouncement(int $participantId): ?string
    {
        $state = $this->getForParticipant($participantId);

        return $state['announcement'] ?? null;
    }

    public function deleteParticipant(int $participantId): void
    {
        $this->redisCacheService->delete(
            $this->cacheKeyGenerator->forTurn($participantId)
        );
    }
}
