<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use App\User\Domain\Exception\UserException;
use App\User\Domain\Model\User;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class AuthenticatedUserService
{
    public function __construct(
        private Security $security
    ) {}

    public function getCurrentUser(): User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw UserException::noUser();
        }

        return $user;
    }
}
