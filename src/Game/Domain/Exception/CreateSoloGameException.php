<?php

declare(strict_types=1);

namespace App\Game\Domain\Exception;

use DomainException;

final class CreateSoloGameException extends DomainException
{
    public static function joinCode(): self
    {
        return new self('exception.failed_to_generate_join_code');
    }
}
