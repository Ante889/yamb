<?php

declare(strict_types = 1);

namespace App\Game\Domain\Repository;

use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Model\ScoreEntry;

interface ScoreEntryRepositoryInterface
{
    public function getByGameParticipantId(int $gameParticipant): array;

    public function existsForParticipantAndPosition(int $participantId, ScoreEntryColumnEnum $column, ScoreEntryFieldEnum $row): bool;

    public function deleteByGameId(int $gameId);

    public function add(ScoreEntry $scoreEntry): self;
    public function save(): void;
}
