<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\JoinWithJoinCodeCommand;
use App\Game\Application\Command\Game\JoinWithJoinCodeCommandHandler;
use App\Game\Infrastructure\Mapper\GameResponseMapper;
use App\Game\Infrastructure\Resource\Game\RoomResource;
use App\Shared\Domain\Mercure\MercureServiceInterface;
use App\Shared\Domain\Mercure\MercureTopics;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class JoinInRoomWithJoinCodeProcessor implements ProcessorInterface
{
    public function __construct(
        private JoinWithJoinCodeCommandHandler $joinCodeCommandHandler,
        private GameResponseMapper             $gameResponseMapper,
        private MercureServiceInterface        $mercureService,
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
        if (!$data instanceof RoomResource) {
            throw new \LogicException('Invalid data');
        }

        $game = $this->joinCodeCommandHandler->handle(
            new JoinWithJoinCodeCommand(
                joinCode: $uriVariables['joinCode']
            )
        );

        $gameMapper = $this->gameResponseMapper->fromEntity(
            $game
        );

        $this->mercureService->create(
            MercureTopics::gameParticipants($game),
            $gameMapper
        );

        return new JsonResponse(
            $gameMapper,
            201
        );
    }
}
