<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\EnterNewEntryCommand;
use App\Game\Application\Command\Game\EnterNewEntryCommandHandler;
use App\Game\Application\Query\Game\GameQuery;
use App\Game\Application\Query\Game\GameQueryHandler;
use App\Game\Application\Query\GameStatus\CurrentGameStatusQuery;
use App\Game\Application\Query\GameStatus\CurrentGameStatusQueryHandler;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Infrastructure\Resource\ScoreEntry\ScoreEntryResource;
use App\Shared\Domain\Mercure\MercureServiceInterface;
use App\Shared\Domain\Mercure\MercureTopics;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class EnterNewDuelEntryProcessor implements ProcessorInterface
{
    public function __construct(
        private EnterNewEntryCommandHandler $enterNewEntryCommandHandler,
        private GameQueryHandler $gameQueryHandler,
        private CurrentGameStatusQueryHandler $currentGameStatusQueryHandler,
        private MercureServiceInterface $mercureService
    ) {
    }


    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): JsonResponse {

        if (!$data instanceof ScoreEntryResource) {
            throw new \LogicException('Invalid input');
        }

        $participantId = (int) $uriVariables['gameParticipantId'];

        $column = ScoreEntryColumnEnum::from($data->column);
        $row = ScoreEntryFieldEnum::from($data->row);

        $game = $this->gameQueryHandler->query(
            new GameQuery(
                gameId: (int) $uriVariables['gameId']
            )
        );

        $command = new EnterNewEntryCommand(
            gameParticipantId: $participantId,
            column: $column,
            row: $row,
            isDuel: true,
            game: $game
        );

        $point = $this->enterNewEntryCommandHandler->handle(
            $command
        );

        $gameStatus = $this->currentGameStatusQueryHandler->query(
            new CurrentGameStatusQuery(
                gameParticipantId: $participantId,
                isDuel: true
            )
        );

        $response = [
            'column' => $column,
            'row' => $row,
            'point' => $point,
            'gameStatus' => $gameStatus
        ];

        $this->mercureService->create(
            MercureTopics::gameParticipantTurn($participantId),
            $response
        );


        return new JsonResponse(
            $response,
            Response::HTTP_CREATED
        );
    }
}