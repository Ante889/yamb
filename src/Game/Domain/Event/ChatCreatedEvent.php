<?php

declare(strict_types=1);

namespace App\Game\Domain\Event;


final readonly class ChatCreatedEvent
{
    public function __construct(
        public int    $chatId,
        public int    $gameId,
        public string $username,
        public string $text
    )
    {
    }
}
