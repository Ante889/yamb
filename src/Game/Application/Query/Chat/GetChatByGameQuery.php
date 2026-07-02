<?php

declare(strict_types=1);

namespace App\Game\Application\Query\Chat;

final readonly class GetChatByGameQuery
{
    public function __construct(
        public ?int $gameId
    )
    {
    }
}
