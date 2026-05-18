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
}
