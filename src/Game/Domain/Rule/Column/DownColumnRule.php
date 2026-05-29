<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Column;

use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;

final readonly class DownColumnRule implements ColumnRuleInterface
{
    public function __construct(
        private ScoreEntryRepositoryInterface $scoreEntryRepository,
        private TurnStateStoreInterface       $store
    )
    {
    }

    public function supports(ScoreEntryColumnEnum $column): bool
    {
        return $column === ScoreEntryColumnEnum::DOWN;
    }

    public function validate(ScoreEntryFieldEnum $row, int $participantId): bool
    {
        $throwNumber = $this->store->getThrowNumberByParticipant($participantId);

        $currentRow = ScoreEntryFieldEnum::from($row->value);

        $previousRow = ScoreEntryFieldEnum::previous($currentRow);

        if ($previousRow === null) {
            return true;
        }

        return $this->scoreEntryRepository->checkUpDownRule($previousRow, ScoreEntryColumnEnum::DOWN, $participantId);
    }

}
