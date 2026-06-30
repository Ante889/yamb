<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game\LobbyHeartBeat;

final readonly class LobbyHeartBeatCommand
{
    public function __construct(
        public ?int $gameParticipantId
    )
    {
    }
}
