<?php

namespace App\Game\Domain\Enum;

enum ScoreEntryColumnEnum: string
{
    case DOWN = 'down';
    case UP = 'up';
    case FREE = 'free';
    case ANNOUNCEMENT = 'announcement';
    case HAND = 'hand';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
