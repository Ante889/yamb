<?php

declare(strict_types=1);

namespace App\Game\Domain\Exception;

use DomainException;

final class GameException extends DomainException
{
    public static function gameNotFound(): self
    {
        return new self('exception.game_not_found');
    }
}
