<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Game\Application\Dto\LeaderboardSoloResult;
use App\Game\Application\Query\Leaderboard\LeaderboardSoloQuery;
use App\Game\Application\Query\Leaderboard\LeaderboardSoloQueryHandler;

final readonly class LeaderboardSoloProvider implements ProviderInterface
{
    public function __construct(
        private LeaderboardSoloQueryHandler $leaderboardSoloQueryHandler
    )
    {
    }

    /**
     * @param Operation $operation
     * @param array<int, mixed> $uriVariables
     * @param array<int, mixed> $context
     * @return array<int, LeaderboardSoloResult>|object[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        return $this->leaderboardSoloQueryHandler->query(
            new LeaderboardSoloQuery(
                type: $uriVariables['type']
            )
        );
    }
}
