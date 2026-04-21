<?php

declare(strict_types=1);

namespace App\User\Application\Service;

use App\User\Domain\Exception\PasswordDoNotMatchException;
use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Repository\UserRepositoryInterface;
use DomainException;

final readonly class UserUniquenessChecker
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function assertNotExists(string $email, string $username): void
    {
        if ($this->repository->existsByEmailOrUsername($email, $username)) {
            throw new UserAlreadyExistsException();
        }
    }
}
