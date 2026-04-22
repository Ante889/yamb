<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use DomainException;

final class InvalidPasswordException extends DomainException
{
    public static function tooShort(): self
    {
        return new self('exception.password.too_short');
    }

    public static function missingUppercase(): self
    {
        return new self('exception.password.missing_uppercase');
    }

    public static function missingLowercase(): self
    {
        return new self('exception.password.missing_lowercase');
    }

    public static function missingNumber(): self
    {
        return new self('exception.password.missing_number');
    }

    public static function doNotMatch(): self
    {
        return new self('exception.password.do_not_match');
    }
}
