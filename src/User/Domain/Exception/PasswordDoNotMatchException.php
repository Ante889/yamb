<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use DomainException;

final class PasswordDoNotMatchException extends DomainException
{
    public function __construct()
    {
        parent::__construct('exception.passwords_do_not_match');
    }
}
