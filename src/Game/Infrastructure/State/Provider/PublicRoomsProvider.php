<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Game\Application\Dto\LeaderboardSoloResult;
use App\Game\Application\Query\Room\GetRoomsQuery;
use App\Game\Application\Query\Room\GetRoomsQueryHandler;
use App\Shared\Domain\Paginator\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class PublicRoomsProvider implements ProviderInterface
{
    public function __construct(
        private GetRoomsQueryHandler $getRoomsQueryHandler,
        private PaginatorInterface   $paginator
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
        return new JsonResponse(
            $this->getRoomsQueryHandler->query(
                new GetRoomsQuery(
                    $this->paginator->getFilter($operation, $context)
                )
            )
        );
    }
}
