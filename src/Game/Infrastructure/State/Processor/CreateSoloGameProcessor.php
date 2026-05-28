<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\NewSoloGameCommand;
use App\Game\Application\Command\Game\NewSoloGameCommandHandler;
use App\Game\Infrastructure\Mapper\GameResponseMapper;
use App\Game\Infrastructure\Resource\Game\StartGameResource;
use Psr\Cache\InvalidArgumentException;
use Random\RandomException;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class CreateSoloGameProcessor implements ProcessorInterface
{
    public function __construct(
        private NewSoloGameCommandHandler $newSoloGameCommandHandler,
        private GameResponseMapper        $gameResponseMapper
    )
    {
    }

    /**
     * @throws RandomException|InvalidArgumentException
     */
    public function process(
        mixed     $data,
        Operation $operation,
        array     $uriVariables = [],
        array     $context = []
    ): JsonResponse
    {
        if (!$data instanceof StartGameResource) {
            throw new \LogicException('Invalid data');
        }

        $game = $this->newSoloGameCommandHandler->handle(
            new NewSoloGameCommand(
                numberOfDice: $data->numberOfDice,
                gameType: $data->gameType
            )
        );

        return new JsonResponse(
            $this->gameResponseMapper->fromEntity($game),
            201
        );
    }
}
