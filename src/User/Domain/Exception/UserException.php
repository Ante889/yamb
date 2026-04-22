<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use DomainException;

final class UserException extends DomainException
{
    public static function noUser(): self
    {
        return new self('exception.user.no_user');
    }

    public static function userAlreadyExists(): self
    {
        return new self('exception.password.user_already_exists');
    }
}
