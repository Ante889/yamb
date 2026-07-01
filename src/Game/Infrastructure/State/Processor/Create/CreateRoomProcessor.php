<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor\Create;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\Create\NewGameCommand;
use App\Game\Application\Command\Game\Create\NewGameCommandHandler;
use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Infrastructure\Mapper\GameResponseMapper;
use App\Game\Infrastructure\Resource\Game\RoomResource;
use App\Shared\Domain\Mercure\MercureServiceInterface;
use App\Shared\Domain\Mercure\MercureTopics;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class CreateRoomProcessor implements ProcessorInterface
{
    public function __construct(
        private MercureServiceInterface $mercureService,
        private NewGameCommandHandler   $commandHandler,
        private GameResponseMapper      $gameResponseMapper
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
        if (!$data instanceof RoomResource) throw new \LogicException('Invalid input');

        $game = $this->commandHandler->handle(
            new NewGameCommand(
                numberOfDice: $data->numberOfDice,
                gameStatus: GameStatusEnum::LOBBY->value,
                gameType: $data->gameType,
                isPublic: $data->isPublic,
                minPlayer: 1,
                maxPlayer: 2,
                isSolo: false
            )
        );

        $gameMapper = $this->gameResponseMapper->fromEntity(
            $game
        );

        if ($data->isPublic) {
            $this->mercureService->create(
                MercureTopics::rooms(),
                $gameMapper
            );
        }

        return new JsonResponse(
            $gameMapper,
            201
        );
    }
}
