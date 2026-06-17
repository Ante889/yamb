<?php

declare(strict_types=1);

namespace App\Game\Application\Query\Leaderboard;


use App\Game\Application\Dto\LeaderboardSoloResult;
use App\Game\Domain\Repository\GameRepositoryInterface;

final readonly class LeaderboardSoloQueryHandler
{
    public function __construct(
        private GameRepositoryInterface $gameRepository
    )
    {
    }

    /**
     * @param LeaderboardSoloQuery $command
     * @return array
     */
    public function query(LeaderboardSoloQuery $command): array
    {
        return LeaderboardSoloResult::fromArray(
            $this->gameRepository->getFirstFiftyByType($command->type)
        );
    }
}
