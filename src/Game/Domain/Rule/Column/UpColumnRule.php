<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Column;

use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;

final readonly class UpColumnRule implements ColumnRuleInterface
{
    public function __construct(
        private ScoreEntryRepositoryInterface $scoreEntryRepository,
        private TurnStateStoreInterface       $store
    )
    {
    }

    public function supports(ScoreEntryColumnEnum $column): bool
    {
        return $column === ScoreEntryColumnEnum::UP;
    }

    public function validate(
        ScoreEntryColumnEnum $column,
        ScoreEntryFieldEnum  $row,
        int                  $participantId,
        Game                 $game
    ): bool
    {
        $currentRow = ScoreEntryFieldEnum::from($row->value);

        $nextRow = ScoreEntryFieldEnum::next($currentRow);

        if ($nextRow === null) {
            return true;
        }

        return $this->scoreEntryRepository->checkUpDownRule($nextRow, ScoreEntryColumnEnum::UP, $participantId);
    }

}
