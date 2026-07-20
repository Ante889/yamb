<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor\Create;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\Create\NewGameCommand;
use App\Game\Application\Command\Game\Create\NewGameCommandHandler;
use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Infrastructure\Mapper\GameResponseMapper;
use App\Game\Infrastructure\Resource\Game\StartGameResource;
use Psr\Cache\InvalidArgumentException;
use Random\RandomException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class CreateSoloGameProcessor implements ProcessorInterface
{
    public function __construct(
        private NewGameCommandHandler $newGameCommandHandler,
        private GameResponseMapper    $gameResponseMapper
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

        $game = $this->newGameCommandHandler->handle(
            new NewGameCommand(
                numberOfDice: $data->numberOfDice,
                gameStatus: GameStatusEnum::IN_PROGRESS->value,
                gameType: $data->gameType,
                isPublic: false,
                minPlayer: 1,
                maxPlayer: 1,
                isSolo: true
            )
        );

        return new JsonResponse(
            $this->gameResponseMapper->fromEntity($game),
            Response::HTTP_CREATED
        );
    }
}
