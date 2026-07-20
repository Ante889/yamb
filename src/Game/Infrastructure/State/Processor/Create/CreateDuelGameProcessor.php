<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor\Create;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\StartNewDuelGameCommand;
use App\Game\Application\Command\Game\StartNewDuelGameCommandHandler;
use App\Game\Infrastructure\Resource\Game\StartDuelGameResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class CreateDuelGameProcessor implements ProcessorInterface
{
    public function __construct(
        private StartNewDuelGameCommandHandler $startNewDuelGameCommandHandler
    )
    {
    }

    public function process(
        mixed     $data,
        Operation $operation,
        array     $uriVariables = [],
        array     $context = []
    ): JsonResponse
    {
        if (!$data instanceof StartDuelGameResource) throw new \LogicException('Invalid input');

        $this->startNewDuelGameCommandHandler->handle(
            new StartNewDuelGameCommand(
                gameId: (int)$uriVariables['gameId']
            )
        );

        return new JsonResponse([
            'success' => true,
            'message' => 'Duel game started successfully',
            'gameId' => $uriVariables['gameId'],
        ], Response::HTTP_CREATED);
    }
}
