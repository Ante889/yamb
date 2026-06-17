<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Game\Infrastructure\Resource\Game\EndGameResource;

final readonly class LeaderboardProvider implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object
    {
        return new EndGameResource(
            gameParticipantId: (int) $uriVariables['gameParticipantId']
        );
    }
}
