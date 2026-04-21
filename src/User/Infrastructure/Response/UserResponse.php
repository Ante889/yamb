<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Response;

final readonly class UserResponse
{
    public function __construct(
        public ?string $username = null,
        public ?string $email = null
    ) {}
}
