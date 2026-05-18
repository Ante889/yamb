<?php

declare(strict_types=1);

namespace App\Game\Domain\Exception;

use DomainException;

final class GameParticipantException extends DomainException
{
    public static function gameParticipantNotFound(): self
    {
        return new self('exception.game_participant_not_found');
    }
}
