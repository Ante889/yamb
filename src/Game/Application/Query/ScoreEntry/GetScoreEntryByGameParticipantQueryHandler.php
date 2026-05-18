<?php

declare(strict_types=1);

namespace App\Game\Application\Query\ScoreEntry;

use App\Game\Application\Dto\ScoreSheetResult;
use App\Game\Domain\Exception\GameParticipantException;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;
use App\Game\Domain\Service\ScoreSheetCalculator;

final readonly class GetScoreEntryByGameParticipantQueryHandler
{
    public function __construct(
        private ScoreEntryRepositoryInterface $scoreEntryRepository,
        private ScoreSheetCalculator          $scoreSheetCalculator
    )
    {
    }

    /**
     * @param GetScoreEntryByGameParticipantQuery $command
     * @return ScoreSheetResult
     */
    public function query(GetScoreEntryByGameParticipantQuery $command): ScoreSheetResult
    {
        if (!$command->gameParticipantId) {
            throw GameParticipantException::gameParticipantNotFound();
        }

        $scoreEntryData = $this->scoreEntryRepository->getByGameParticipantId($command->gameParticipantId);

        return $this->scoreSheetCalculator->calculate($scoreEntryData);
    }
}
