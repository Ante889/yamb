<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Game\Application\Dto\LeaderboardSoloResult;
use App\Game\Application\Query\Leaderboard\LeaderboardSoloQuery;
use App\Game\Application\Query\Leaderboard\LeaderboardSoloQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final readonly class LeaderboardSoloProvider implements ProviderInterface
{
    public function __construct(
        private LeaderboardSoloQueryHandler $leaderboardSoloQueryHandler,
        private HubInterface $hub
    )
    {
    }

    /**
     * @param Operation $operation
     * @param array<int, mixed> $uriVariables
     * @param array<int, mixed> $context
     * @return array<int, LeaderboardSoloResult>|object[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        $update = new Update(
            'chat',
            json_encode([
                'message' => 'Mercure radi 🚀',
                'time' => time()
            ])
        );

        $this->hub->publish($update);

        return new JsonResponse('Sent to Mercure');

        return new JsonResponse($this->leaderboardSoloQueryHandler->query(
            new LeaderboardSoloQuery(
                type: $uriVariables['type'],
                dice: $uriVariables['dice'],
            )
        ), 200);
    }
}
