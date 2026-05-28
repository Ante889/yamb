<?php

declare(strict_types=1);

namespace App\Game\Domain\Exception;

use DomainException;

final class EnterNewEntryException extends DomainException
{
    public static function fieldAlreadyUsed(): self
    {
        return new self('exception.field_already_used');
    }

    public static function noDices(): self
    {
        return new self('exception.no_dices');
    }
}
