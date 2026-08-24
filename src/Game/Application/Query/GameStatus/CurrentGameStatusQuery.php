<?php

declare(strict_types=1);

namespace App\Game\Application\Query\GameStatus;

final readonly class CurrentGameStatusQuery
{
    public function __construct(
        public ?int  $gameParticipantId,
        public ?bool $isDuel = false
    )
    {
    }
}
