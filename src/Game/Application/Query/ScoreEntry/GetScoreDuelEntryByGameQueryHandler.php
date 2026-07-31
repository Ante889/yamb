<?php

declare(strict_types=1);

namespace App\Game\Application\Query\ScoreEntry;

use App\Game\Application\Dto\GameParticipantResult;
use App\Game\Application\Dto\ScoreSheetResult;
use App\Game\Application\Query\GameStatus\CurrentGameStatusQuery;
use App\Game\Application\Query\GameStatus\CurrentGameStatusQueryHandler;
use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Model\GameParticipant;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;
use App\Game\Domain\Service\ScoreSheetCalculator;
use App\Game\Domain\Validator\GameValidator;
use App\User\Infrastructure\Security\AuthenticatedUserService;
use Psr\Cache\InvalidArgumentException;

final readonly class GetScoreDuelEntryByGameQueryHandler
{
    public function __construct(
        private ScoreEntryRepositoryInterface $scoreEntryRepository,
        private ScoreSheetCalculator          $scoreSheetCalculator,
        private TurnStateStoreInterface       $store,
        private GameRepositoryInterface       $gameRepository,
        private AuthenticatedUserService      $userService,
        private GameValidator                 $gameValidator,
        private CurrentGameStatusQueryHandler $currentGameStatusQueryHandler
    )
    {
    }

    /**
     * @param GetScoreDuelEntryByGameQuery $command
     * @return array<int, ScoreSheetResult>
     * @throws InvalidArgumentException
     */
    public function query(GetScoreDuelEntryByGameQuery $command): array
    {
        if (!$command->gameId) {
            throw GameException::gameNotFound();
        }

        $game = $this->gameRepository->findById($command->gameId);

        $this->gameValidator->isPlayerInRoom($game, $this->userService->getCurrentUser());

        $returnData = [];

        /** @var GameParticipant $participant */
        foreach ($game->getParticipants() as $participant) {
            $participantId = $participant->getId();
            $scoreEntryData = $this->scoreEntryRepository->getByGameParticipantId($participantId);

            $commandStatus = new CurrentGameStatusQuery(
                gameParticipantId: (int)$participantId
            );

            $participantData = GameParticipantResult::fillData($participant);

            $scoreSheetResult = $this->scoreSheetCalculator->calculate(
                $scoreEntryData,
                $this->store->getDicesByParticipant($participantId) ?? [],
                $game->getDiceNumber(),
                $this->store->getThrowNumberByParticipant($participantId) ?? 0,
                $game->getGameType(),
                $this->store->getAnnouncement($participantId),
                $this->currentGameStatusQueryHandler->query($commandStatus)
            );

            $returnData[] = array_merge(
                get_object_vars($participantData),
                get_object_vars($scoreSheetResult)
            );
        }

        return $returnData;
    }
}
