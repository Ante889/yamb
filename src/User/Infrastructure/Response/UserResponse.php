<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Response;

use App\User\Domain\Model\User;

final readonly class UserResponse
{
    public function __construct(
        public ?int    $key = null,
        public ?string $username = null,
        public ?string $email = null,
    )
    {
    }

    public static function returnModel(User $user): self
    {
        return new self(
            key: $user->getId(),
            username: $user->getUsername(),
            email: $user->getEmail()
        );
    }
}
