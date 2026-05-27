<?php

declare(strict_types = 1);

namespace App\Game\Domain\Repository;

use App\Game\Domain\Model\ScoreEntry;

interface ScoreEntryRepositoryInterface
{
    public function getByGameParticipantId(int $gameParticipant): array;

    public function deleteByGameId(int $gameId);

    public function add(ScoreEntry $scoreEntry): self;
    public function save(): void;
}
