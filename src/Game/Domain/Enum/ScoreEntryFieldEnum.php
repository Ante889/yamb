<?php

namespace App\Game\Domain\Enum;

enum ScoreEntryFieldEnum: string
{
    case ONES = 'ones';
    case TWOS = 'twos';
    case THREES = 'threes';
    case FOURS = 'fours';
    case FIVES = 'fives';
    case SIXES = 'sixes';

    case MAX = 'max';
    case MIN = 'min';

    case TWO_PAIRS = 'two_pairs';
    case TRIS = 'tris';
    case STRAIGHT = 'straight';
    case FULL = 'full';
    case POKER = 'poker';
    case YAMB = 'yamb';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function ordered(): array
    {
        return [
            self::ONES,
            self::TWOS,
            self::THREES,
            self::FOURS,
            self::FIVES,
            self::SIXES,
            self::MAX,
            self::MIN,
            self::TWO_PAIRS,
            self::TRIS,
            self::STRAIGHT,
            self::FULL,
            self::POKER,
            self::YAMB,
        ];
    }

    public static function previous(self $current): ?self
    {
        $ordered = self::ordered();

        foreach ($ordered as $index => $field) {
            if ($field === $current) {
                return $ordered[$index - 1] ?? null;
            }
        }

        return null;
    }

    public static function next(self $current): ?self
    {
        $ordered = self::ordered();

        foreach ($ordered as $index => $field) {
            if ($field === $current) {
                return $ordered[$index + 1] ?? null;
            }
        }

        return null;
    }
}
