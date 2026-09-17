<?php

declare(strict_types=1);

namespace App\User\Application\Query;

use App\User\Domain\Exception\UserException;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;

final readonly class GetUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function query(GetUserCommand $query): User
    {
        $user = $this->userRepository->findById($query->id);

        if (!$user) {
            throw UserException::noUser();
        }

        return $user;
    }
}
