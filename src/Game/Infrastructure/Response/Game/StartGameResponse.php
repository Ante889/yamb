<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Response\Game;

use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;
use App\Shared\Domain\Cache\RedisCacheServiceInterface;
use App\Shared\Infrastructure\Cache\CacheKeyGenerator;
use Psr\Cache\InvalidArgumentException;

final readonly class StartGameResponse
{
    public function __construct(
        private CacheKeyGenerator          $cacheKeyGenerator,
        private RedisCacheServiceInterface $redisCacheService
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function fromEntity(Game $game): array
    {
        return [
            'key' => $game->getId(),
            'numberOfDice' => $game->getDiceNumber(),
            'participants' => array_map(
            static fn(GameParticipant $participant): array => [
                    'id' => $participant->getId(),
                    'userId' => $participant->getUser()->getId(),
                    'status' => $participant->getStatus(),
                    'position' => $participant->getPosition(),
                    'diceData' => $this->redisCacheService->get($this->cacheKeyGenerator->forTurn($participant->getId()))
                ],
                $game->getParticipants()->toArray()
            ),
        ];
    }
}
