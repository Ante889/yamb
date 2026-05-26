<?php

declare(strict_types=1);

namespace App\Game\Domain\Exception;

use DomainException;

final class ThrowDiceException extends DomainException
{
    public static function invalidDiceNumber(): self
    {
        return new self('exception.wrong_dice_number');
    }

    public static function maxThrowsReached(): self
    {
        return new self('exception.max_throws');
    }
}
