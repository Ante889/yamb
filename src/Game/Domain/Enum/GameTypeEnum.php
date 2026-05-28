<?php

namespace App\Game\Domain\Enum;

enum GameTypeEnum: string
{
    case SINGLE_COLUMN = 'SINGLE_COLUMN';
    case CLASSIC = 'CLASSIC';
    case YAMB_PLUS = 'YAMB_PLUS';

    public function columns(): array
    {
        return match ($this) {
            self::SINGLE_COLUMN => [
                ScoreEntryColumnEnum::FREE,
            ],

            self::CLASSIC => [
                ScoreEntryColumnEnum::DOWN,
                ScoreEntryColumnEnum::UP,
                ScoreEntryColumnEnum::FREE,
                ScoreEntryColumnEnum::ANNOUNCEMENT,
            ],

            self::YAMB_PLUS => ScoreEntryColumnEnum::cases(),
        };
    }
}
