<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use App\User\Application\Service\UserUniquenessChecker;
use App\User\Domain\Enum\UserRoleEnum;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Service\RegistrationPolicy;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class RegistrationCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RegistrationPolicy      $policy,
        private UserUniquenessChecker   $uniquenessChecker,
        private UserPasswordHasherInterface $hasher,
    )
    {
    }

    public function __invoke(RegistrationCommand $command): void
    {
        $this->uniquenessChecker->assertNotExists($command->username, $command->email);
        $this->policy->validatePassword($command->password, $command->repeatPassword);

        $user = (new User())
            ->setEmail($command->email)
            ->setUsername($command->username)
            ->setRoles([UserRoleEnum::USER->value]);

        $user->setPassword(
            $this->hasher->hashPassword($user, $command->password)
        );

        $this->userRepository
            ->add($user)
            ->save();
    }
}
