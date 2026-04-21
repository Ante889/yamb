<?php

declare(strict_types = 1);

namespace App\User\Domain\Repository;

use App\User\Domain\Model\User;

interface UserRepositoryInterface
{
    public function existsByEmailOrUsername(string $email, string $username): ?User;
    public function add(User $user): self;
    public function save(): void;
}
