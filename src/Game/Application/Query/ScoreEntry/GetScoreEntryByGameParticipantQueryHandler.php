<?php

declare(strict_types=1);

namespace App\Game\Application\Query\ScoreEntry;

use App\Game\Application\Dto\ScoreSheetResult;
use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Exception\GameParticipantException;
use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;
use App\Game\Domain\Service\ScoreSheetCalculator;
use Psr\Cache\InvalidArgumentException;

final readonly class GetScoreEntryByGameParticipantQueryHandler
{
    public function __construct(
        private ScoreEntryRepositoryInterface      $scoreEntryRepository,
        private ScoreSheetCalculator               $scoreSheetCalculator,
        private TurnStateStoreInterface            $store,
        private GameRepositoryInterface            $gameRepository,
    )
    {
    }

    /**
     * @param GetScoreEntryByGameParticipantQuery $command
     * @return ScoreSheetResult
     * @throws InvalidArgumentException
     */
    public function query(GetScoreEntryByGameParticipantQuery $command): ScoreSheetResult
    {
        if (!$command->gameParticipantId) {
            throw GameParticipantException::gameParticipantNotFound();
        }

        $scoreEntryData = $this->scoreEntryRepository->getByGameParticipantId($command->gameParticipantId);

        $game = $this->gameRepository->gameByGameParticipant($command->gameParticipantId);

        $state = $this->store->getForParticipant($command->gameParticipantId);
        return $this->scoreSheetCalculator->calculate(
            $scoreEntryData,
            $state['dices'] ?? [],
            $game->getDiceNumber() ?? 6,
            $state['throwNumber'] ?? 0,
            $game->getGameType()
        );
    }
}
