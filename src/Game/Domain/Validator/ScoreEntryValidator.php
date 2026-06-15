<?php

declare(strict_types=1);

namespace App\Game\Domain\Validator;

use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Exception\EnterNewEntryException;
use App\Game\Domain\Exception\ThrowDiceException;
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

    public function remainingColumns(
        int $throwNumber,
        array $remainingColumns,
        ?string $announcement
    ): void
    {
        $remaining = array_values($remainingColumns);
        $remainingCount = count($remaining);

        $hasAnnouncement = in_array(ScoreEntryColumnEnum::ANNOUNCEMENT, $remaining, true);
        $hasHand = in_array(ScoreEntryColumnEnum::HAND, $remaining, true);

        $onlyAnnouncement = $hasAnnouncement && !$hasHand;
        $onlyHand = $hasHand && !$hasAnnouncement;

        $isSecondThrow = $throwNumber === 1;
        $noAnnouncement = $announcement === null;

        if ($hasAnnouncement && $hasHand) {
            if ($remainingCount === 2 && $isSecondThrow && $noAnnouncement) {
                throw ThrowDiceException::createAnnouncementOrHand();
            }
        }

        if ($onlyHand) {
            if ($remainingCount === 1 && $isSecondThrow) {
                throw ThrowDiceException::createHand();
            }
        }

        if ($onlyAnnouncement) {
            if ($remainingCount === 1 && $noAnnouncement) {
                throw ThrowDiceException::createAnnouncement();
            }
        }
    }
}
