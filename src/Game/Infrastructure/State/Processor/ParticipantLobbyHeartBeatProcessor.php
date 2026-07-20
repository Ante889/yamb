<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\LobbyHeartBeat\LobbyHeartBeatCommand;
use App\Game\Application\Command\Game\LobbyHeartBeat\LobbyHeartBeatCommandHandler;
use App\Game\Infrastructure\Resource\Game\GameParticipantResource;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class ParticipantLobbyHeartBeatProcessor implements ProcessorInterface
{
    public function __construct(
        private LobbyHeartBeatCommandHandler $lobbyHeartBeatCommandHandler
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function process(
        mixed     $data,
        Operation $operation,
        array     $uriVariables = [],
        array     $context = []
    ): JsonResponse
    {
        if (!$data instanceof GameParticipantResource) {
            throw new \LogicException('Invalid data');
        }

        $this->lobbyHeartBeatCommandHandler->handle(
            new LobbyHeartBeatCommand(
                gameParticipantId: (int)$uriVariables['gameParticipantId']
            )
        );

        return new JsonResponse([
           'created' => true
       ], Response::HTTP_OK);
    }
}
