<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\EnterNewEntryCommand;
use App\Game\Application\Command\Game\EnterNewEntryCommandHandler;
use App\Game\Application\Query\GameStatus\CurrentGameStatusQuery;
use App\Game\Application\Query\GameStatus\CurrentGameStatusQueryHandler;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Infrastructure\Resource\ScoreEntry\ScoreEntryResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class EnterNewEntryProcessor implements ProcessorInterface
{
    public function __construct(
        private EnterNewEntryCommandHandler   $enterNewEntryCommandHandler,
        private CurrentGameStatusQueryHandler $currentGameStatusQueryHandler
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
        if (!$data instanceof ScoreEntryResource) {
            throw new \LogicException('Invalid input');
        }

        $command = new EnterNewEntryCommand(
            gameParticipantId: (int)$uriVariables['gameParticipantId'],
            column: ScoreEntryColumnEnum::from($data->column),
            row: ScoreEntryFieldEnum::from($data->row)
        );

        $commandStatus = new CurrentGameStatusQuery(
            gameParticipantId: (int)$uriVariables['gameParticipantId']
        );

        return new JsonResponse(
            [
                'column' => ScoreEntryColumnEnum::from($data->column),
                'row' => ScoreEntryFieldEnum::from($data->row),
                'point' => $this->enterNewEntryCommandHandler->handle($command),
                'gameStatus' => $this->currentGameStatusQueryHandler->query($commandStatus)
            ],
            Response::HTTP_CREATED
        );
    }
}
