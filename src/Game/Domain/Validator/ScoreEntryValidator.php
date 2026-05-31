<?php

declare(strict_types=1);

namespace App\Game\Domain\Validator;

use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Exception\EnterNewEntryException;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;

final readonly class ScoreEntryValidator
{
    public function __construct(
        private ScoreEntryRepositoryInterface $repository
    )
    {
    }

    public function assertPositionFree(int $participantId, ScoreEntryColumnEnum $column, ScoreEntryFieldEnum $row): void
    {
        if ($this->repository->existsForParticipantAndPosition($participantId, $column, $row)) {
            throw EnterNewEntryException::fieldAlreadyUsed();
        }
    }

    public function neverThrow(int $throwNumber): void
    {
        if ($throwNumber === 0) {
            throw EnterNewEntryException::noDices();
        }
    }
}
