<?php

declare(strict_types=1);

namespace App\Mercure\Infrastructure\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Mercure\Infrastructure\Service\LcobucciMercureTokenGenerator;
use App\Shared\Domain\Mercure\MercureTopics;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class MercureTokenProvider implements ProviderInterface
{
    public function __construct(
        private LcobucciMercureTokenGenerator $tokenGenerator
    ) {}

    /**
     * @param Operation $operation
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     * @return JsonResponse
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        $gameId = $context['filters']['gameId'] ?? null;

        $topics = [
            MercureTopics::rooms(),
            MercureTopics::gameParticipantsPattern(),
        ];

        if ($gameId) {
            $topics[] = MercureTopics::gameChat((int) $gameId);
        }

        return new JsonResponse([
            'token' => $this->tokenGenerator->generate($topics)
        ]);
    }
}
