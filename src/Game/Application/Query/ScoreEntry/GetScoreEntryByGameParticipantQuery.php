<?php

declare(strict_types=1);

namespace App\Game\Application\Query\ScoreEntry;

final readonly class GetScoreEntryByGameParticipantQuery
{
    public function __construct(
        public ?int $gameParticipantId
    )
    {
    }
}
