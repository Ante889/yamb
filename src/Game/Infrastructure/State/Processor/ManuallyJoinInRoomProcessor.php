<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\JoinWithGameIdCommand;
use App\Game\Application\Command\Game\JoinWithGameIdCommandHandler;
use App\Game\Infrastructure\Mapper\GameResponseMapper;
use App\Game\Infrastructure\Resource\Game\RoomResource;
use App\Shared\Domain\Mercure\MercureServiceInterface;
use App\Shared\Domain\Mercure\MercureTopics;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class ManuallyJoinInRoomProcessor implements ProcessorInterface
{
    public function __construct(
        private JoinWithGameIdCommandHandler $joinWithGameIdCommandHandler,
        private GameResponseMapper           $gameResponseMapper,
        private MercureServiceInterface      $mercureService,
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

        $game = $this->joinWithGameIdCommandHandler->handle(
            new JoinWithGameIdCommand(
                gameId: (int)$uriVariables['gameId']
            )
        );

        $gameMapper = $this->gameResponseMapper->fromEntity(
            $game
        );

        $this->mercureService->update(
            MercureTopics::rooms(),
            $gameMapper
        );

        return new JsonResponse(
            $gameMapper,
            201
        );
    }
}
