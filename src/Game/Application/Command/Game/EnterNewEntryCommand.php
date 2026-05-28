<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;

final readonly class EnterNewEntryCommand
{
    public function __construct(
        public int                  $gameParticipantId,
        public ScoreEntryColumnEnum $column,
        public ScoreEntryFieldEnum  $row
    )
    {
    }
}
