<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Game\Application\Query\ScoreEntry\GetScoreDuelEntryByGameQuery;
use App\Game\Application\Query\ScoreEntry\GetScoreDuelEntryByGameQueryHandler;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class ScoreDuelEntryByGameProvider implements ProviderInterface
{
    public function __construct(
        private GetScoreDuelEntryByGameQueryHandler $getScoreDuelEntryByGameQueryHandler
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        return new JsonResponse(
            $this->getScoreDuelEntryByGameQueryHandler->query(
                new GetScoreDuelEntryByGameQuery(
                    gameId: $uriVariables['gameId']
                )
            )
        );
    }
}
