<?php

declare(strict_types=1);

namespace App\Game\Application\Query\GameStatus;

use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Exception\GameParticipantException;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;
use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;
use App\Game\Domain\Service\CalculatePointsForEntryService;
use App\Game\Domain\Service\GameStatusService;
use App\Shared\Domain\Mercure\MercureServiceInterface;
use App\Shared\Domain\Mercure\MercureTopics;

final readonly class CurrentGameStatusQueryHandler
{
    public function __construct(
        private ScoreEntryRepositoryInterface      $scoreEntryRepository,
        private GameStatusService                  $gameStatusService,
        private GameRepositoryInterface            $gameRepository,
        private GameParticipantRepositoryInterface $gameParticipantRepository,
        private CalculatePointsForEntryService     $calculatePointsForEntryService,
        private MercureServiceInterface            $mercureService
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

        if($game->getGameStatus() === GameStatusEnum::FINISHED->value) {
            return GameStatusEnum::FINISHED->value;
        }

        if ($command->isDuel) {
            $status = $this->duel($game);
        } else {
            $status = $this->solo($command, $game);
        }

        return $status;

    }

    private function duel(Game $game): string
    {
        $scoreEntries = $this->scoreEntryRepository->getByGame($game->getId());
        $isGameFinished = $this->gameStatusService->isGameFinished(
            $game,
            count($scoreEntries),
            true
        );

        if ($isGameFinished) {
            /** @var GameParticipant $participant */
            foreach ($game->getParticipants() as $participant) {
                $score = $this->calculatePointsForEntryService->calculateTotalScore(
                    $this->scoreEntryRepository->getByGameParticipantId($participant->getId())
                );

                $game->finishParticipant($participant, $score);
            }

            $this->mercureService->create(
                MercureTopics::finishedGame($game->getId()),
                [
                    'success' => 'Success finished game'
                ]
            );

            $game->finishGame();

            $this->gameRepository->save();

            return GameStatusEnum::FINISHED->value;
        }

        return GameStatusEnum::IN_PROGRESS->value;
    }

    private function solo(CurrentGameStatusQuery $command, Game $game): string
    {
        $scoreEntries = $this->scoreEntryRepository->getByGameParticipantId($command->gameParticipantId);
        $isGameFinished = $this->gameStatusService->isGameFinished(
            $game,
            count($scoreEntries),
            false
        );

        if ($isGameFinished) {
            $gameParticipant = $this->gameParticipantRepository->findById($command->gameParticipantId);
            $game->finish($gameParticipant, $this->calculatePointsForEntryService->calculateTotalScore(
                $scoreEntries
            ));

            $this->gameRepository->save();

            return GameStatusEnum::FINISHED->value;
        }

        return GameStatusEnum::IN_PROGRESS->value;
    }
}
