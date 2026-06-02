<?php

declare(strict_types=1);

namespace App\Game\Domain\Rule\Column;

use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Model\Game;

final readonly class HandColumnRule implements ColumnRuleInterface
{
    public function __construct(
        private TurnStateStoreInterface $store
    )
    {
    }

    public function supports(ScoreEntryColumnEnum $column): bool
    {
        return $column === ScoreEntryColumnEnum::HAND;
    }

    public function validate(
        ScoreEntryColumnEnum $column,
        ScoreEntryFieldEnum  $row,
        int                  $participantId,
        Game                 $game
    ): bool
    {
        if (!in_array($column, $game->getGameType()->columns(), true)) {
            return false;
        }

        return $this->store->getThrowNumberByParticipant($participantId) === 1;
    }
}
