<?php

declare(strict_types=1);

namespace App\User\Application\Command;

final readonly class RegistrationCommand
{
    public function __construct(
        public string $username,
        public string $email,
        public string $password,
        public string $repeatPassword
    )
    {
    }
}
