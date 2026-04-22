<?php

declare(strict_types=1);

namespace App\User\Infrastructure\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\User\Infrastructure\Resource\UserResource;
use App\User\Infrastructure\Response\UserResponse;
use App\User\Infrastructure\Security\AuthenticatedUserService;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class CurrentUserDataProvider implements ProviderInterface
{
    public function __construct(
        private AuthenticatedUserService $authenticatedUserService
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
        if ($operation->getClass() !== UserResource::class) throw new \LogicException();

        return new JsonResponse(
            UserResponse::returnModel($this->authenticatedUserService->getCurrentUser()), 200
        );
    }
}
