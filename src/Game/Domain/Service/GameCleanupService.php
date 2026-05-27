<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;

final readonly class GameCleanupService
{
    public function __construct(
        private GameRepositoryInterface            $gameRepository,
        private GameParticipantRepositoryInterface $gameParticipantRepository,
        private ScoreEntryRepositoryInterface      $scoreEntryRepository,

    )
    {
    }

    public function cleanScoreEntry(int $gameId): void
    {
        $this->scoreEntryRepository->deleteByGameId($gameId);
    }

    public function cleanParticipants(int $gameId): void
    {
        $this->gameParticipantRepository->deleteByGameId($gameId);
    }

    public function cleanGame(int $gameId): void
    {
        $this->gameRepository->deleteById($gameId);
    }
}
