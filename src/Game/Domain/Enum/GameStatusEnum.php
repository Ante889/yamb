<?php

namespace App\Game\Domain\Enum;

enum GameStatusEnum: string
{
    case LOBBY = 'LOBBY';
    case IN_PROGRESS = 'IN_PROGRESS';
    case PAUSED = 'PAUSED';
    case CANCELLED = 'CANCELLED';
    case FINISHED = 'FINISHED';
}
