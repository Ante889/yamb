<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Game\Application\Query\Game\GameQuery;
use App\Game\Application\Query\Game\GameQueryHandler;
use App\Game\Infrastructure\Mapper\GameResponseMapper;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class GameProvider implements ProviderInterface
{
    public function __construct(
        private GameResponseMapper $gameResponseMapper,
        private GameQueryHandler   $gameQueryHandler
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object
    {
        return new JsonResponse(
            $this->gameResponseMapper->fromEntity(
                $this->gameQueryHandler->query(
                    new GameQuery(
                        gameId: (int)$uriVariables['id']
                    )
                )
            )
        );
    }
}
