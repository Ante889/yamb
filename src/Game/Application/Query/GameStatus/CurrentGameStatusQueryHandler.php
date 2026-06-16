<?php

declare(strict_types=1);

namespace App\Game\Application\Query\GameStatus;

use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Exception\GameParticipantException;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;
use App\Game\Domain\Service\CalculatePointsForEntryService;
use App\Game\Domain\Service\GameStatusService;

final readonly class CurrentGameStatusQueryHandler
{
    public function __construct(
        private ScoreEntryRepositoryInterface  $scoreEntryRepository,
        private GameStatusService              $gameStatusService,
        private GameRepositoryInterface        $gameRepository,
        private CalculatePointsForEntryService $calculatePointsForEntryService,
    )
    {
    }

    /**
     * @param CurrentGameStatusQuery $command
     * @return string
     */
    public function query(CurrentGameStatusQuery $command): string
    {
        if (!$command->gameParticipantId) {
            throw GameParticipantException::gameParticipantNotFound();
        }

        $game = $this->gameRepository->gameByGameParticipant($command->gameParticipantId);
        $scoreEntries = $this->scoreEntryRepository->getByGameParticipantId($command->gameParticipantId);

        $isGameFinished = $this->gameStatusService->isGameFinished(
            $game,
            count($scoreEntries)
        );

        if ($isGameFinished) {
            $game->finish($this->calculatePointsForEntryService->getTotal(
                $scoreEntries
            ));

            $this->gameRepository->save();

            return GameStatusEnum::FINISHED->value;
        }

        return GameStatusEnum::IN_PROGRESS->value;
    }
}
