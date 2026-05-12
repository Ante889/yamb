<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\NewSoloGameCommand;
use App\Game\Application\Command\Game\NewSoloGameCommandHandler;
use App\Game\Infrastructure\Resource\Game\StartGameResource;
use App\Game\Infrastructure\Response\Game\StartGameResponse;
use Random\RandomException;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class CreateSoloGameProcessor implements ProcessorInterface
{
    /**
     * @implements ProcessorInterface<StartGameResource, JsonResponse>
     */
    public function __construct(
        private NewSoloGameCommandHandler $newSoloGameCommandHandler
    )
    {
    }

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return JsonResponse
     * @throws RandomException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        if (!$data instanceof StartGameResource) {
            throw new \LogicException('Invalid data');
        }

        $game = $this->newSoloGameCommandHandler->handle(
            new NewSoloGameCommand(
                numberOfDice: $data->numberOfDice
            )
        );

        return new JsonResponse(StartGameResponse::fromEntity($game), 201);
    }
}
