<?php

declare(strict_types=1);

namespace App\Game\Application\Query\GameParticipant;

use App\Game\Application\Dto\GameForDeleteResult;
use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Shared\Infrastructure\Cache\CacheKeyGenerator;
use App\Shared\Infrastructure\Cache\RedisCacheService;
use Psr\Cache\InvalidArgumentException;

final readonly class AllParticipantsWithExpiredHeartBeatQueryHandler
{
    public function __construct(
        private GameRepositoryInterface $gameRepository,
        private RedisCacheService $redisCacheService,
        private CacheKeyGenerator $cacheKeyGenerator,
    ) {
    }

    /**
     * @return GameForDeleteResult
     * @throws InvalidArgumentException
     */
    public function query(string $status): GameForDeleteResult
    {
        $expiredParticipants = [];
        $emptyGames = [];

        foreach ($this->gameRepository->getByStatus($status) as $game) {
            $participants = $game->getParticipants();

            if (count($participants) === 0) {
                $emptyGames[] = $game;
                continue;
            }

            foreach ($participants as $participant) {
                if ($this->hasActiveHeartbeat($participant)) {
                    continue;
                }

                $expiredParticipants[] = $participant;
            }
        }

        return new GameForDeleteResult($expiredParticipants, $emptyGames);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function hasActiveHeartbeat(GameParticipant $participant): bool
    {
        return (bool) $this->redisCacheService->get(
            $this->cacheKeyGenerator->forHeartBeat($participant->getId())
        );
    }
}
