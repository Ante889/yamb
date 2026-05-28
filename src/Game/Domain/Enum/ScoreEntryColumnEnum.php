<?php

namespace App\Game\Domain\Enum;

enum ScoreEntryColumnEnum: string
{
    case DOWN = 'down';
    case UP = 'up';
    case FREE = 'free';
    case ANNOUNCEMENT = 'announcement';
    case HAND = 'hand';
}
