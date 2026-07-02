<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game\Create;

final readonly class NewChatCommand
{
    public function __construct(
        public int    $gameParticipantId,
        public string $text
    )
    {
    }
}
