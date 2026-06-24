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

    public static function gameIsFinished(): self
    {
        return new self('exception.game_is_finished');
    }

    public static function gameIsFull(): self
    {
        return new self('exception.game_is_full');
    }

    public static function gameIsAlreadyStarted(): self
    {
        return new self('exception.game_already_started');
    }
}
