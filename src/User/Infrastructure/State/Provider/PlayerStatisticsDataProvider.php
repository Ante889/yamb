<?php


declare(strict_types=1);

namespace App\User\Infrastructure\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\User\Application\Query\GetUserCommand;
use App\User\Application\Query\GetUserCommandHandler;
use App\User\Application\Query\GetUserStatisticsQuery;
use App\User\Application\Query\GetUserStatisticsQueryHandler;
use App\User\Infrastructure\Resource\PlayerResource;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class PlayerStatisticsDataProvider implements ProviderInterface
{
    public function __construct(
        private GetUserCommandHandler $getUserCommandHandler,
        private GetUserStatisticsQueryHandler $getUserStatisticsQueryHandler
    )
    {
    }

    /**
     * @param Operation $operation
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     * @return JsonResponse
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        if ($operation->getClass() !== PlayerResource::class) throw new \LogicException();

        $user = $this->getUserCommandHandler->query(
            new GetUserCommand(
                (int)$uriVariables['id']
            )
        );

        return new JsonResponse(
            $this->getUserStatisticsQueryHandler->query(
                new GetUserStatisticsQuery(
                    $user
                )
            )
        );
    }
}
