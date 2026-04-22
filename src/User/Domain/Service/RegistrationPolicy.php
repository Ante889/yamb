<?php

declare(strict_types=1);

namespace App\User\Domain\Service;

use App\User\Domain\Exception\InvalidPasswordException;
use App\User\Domain\Exception\UserException;
use App\User\Domain\Repository\UserRepositoryInterface;

final readonly class RegistrationPolicy
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function validate(
        string $email,
        string $username,
        string $password,
        string $repeat
    ): void {
        if ($this->userRepository->existsByEmailOrUsername($email, $username)) {
            throw UserException::userAlreadyExists();
        }

        if ($password !== $repeat) {
            throw InvalidPasswordException::doNotMatch();
        }

        if (strlen($password) < 8) {
            throw InvalidPasswordException::tooShort();
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw InvalidPasswordException::missingUppercase();
        }

        if (!preg_match('/[a-z]/', $password)) {
            throw InvalidPasswordException::missingLowercase();
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw InvalidPasswordException::missingNumber();
        }
    }
}
