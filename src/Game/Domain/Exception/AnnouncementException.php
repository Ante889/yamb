<?php

declare(strict_types=1);

namespace App\Game\Domain\Exception;

use DomainException;

final class AnnouncementException extends DomainException
{
    public static function maxThrowNumberIsReached(): self
    {
        return new self('exception.max_throw_number_is_reached');
    }

    public static function alreadySet(): self
    {
        return new self('exception.announcement_already_set');
    }
}
