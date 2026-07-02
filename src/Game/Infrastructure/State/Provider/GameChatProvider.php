<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Game\Application\Query\Chat\GetChatByGameQuery;
use App\Game\Application\Query\Chat\GetChatByGameQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class GameChatProvider implements ProviderInterface
{
    public function __construct(
        private GetChatByGameQueryHandler $getChatByGameQueryHandler
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        return new JsonResponse(
            $this->getChatByGameQueryHandler->query(
                new GetChatByGameQuery(
                    (int)$uriVariables['gameId']
                )
            )
        );
    }
}
