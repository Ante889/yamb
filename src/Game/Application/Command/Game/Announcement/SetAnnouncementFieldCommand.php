<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game\Announcement;

use App\Game\Domain\Enum\ScoreEntryFieldEnum;

final readonly class SetAnnouncementFieldCommand
{
    public function __construct(
        public int                 $gameParticipantId,
        public ScoreEntryFieldEnum $row
    )
    {
    }
}
