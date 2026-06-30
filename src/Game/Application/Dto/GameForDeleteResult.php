<?php

declare(strict_types=1);

namespace App\Game\Application\Dto;

final readonly class GameForDeleteResult
{
    public function __construct(
        public array   $expiredParticipants,
        public array   $emptyGames
    )
    {
    }
}
