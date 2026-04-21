<?php

declare(strict_types=1);

namespace App\User\Domain\Service;

use App\User\Domain\Exception\PasswordDoNotMatchException;
use App\User\Domain\Exception\PasswordMissingLowercaseException;
use App\User\Domain\Exception\PasswordMissingNumberException;
use App\User\Domain\Exception\PasswordMissingUppercaseException;
use App\User\Domain\Exception\PasswordTooShortException;

final readonly class RegistrationPolicy
{
    public function validatePassword(string $password, string $repeat): void
    {
        if ($password !== $repeat) {
            throw new PasswordDoNotMatchException();
        }

        if (strlen($password) < 8) {
            throw new PasswordTooShortException();
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new PasswordMissingUppercaseException();
        }

        if (!preg_match('/[a-z]/', $password)) {
            throw new PasswordMissingLowercaseException();
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new PasswordMissingNumberException();
        }
    }
}
