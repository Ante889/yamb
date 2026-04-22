<?php

declare(strict_types=1);

namespace App\Core\User;

use App\User\Domain\Exception\NoUserException;
use App\User\Domain\Model\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class UserCoreService implements UserCoreServiceInterface
{
    public UserInterface|null $currentUser = null;

    /** @var array<mixed> $communityIds */
    public array $communityIds = [];

    public function __construct(
        private readonly Security $security
    )
    {
    }

    public function getCurrentUser(): User
    {
        if (empty($this->currentUser)) {
            $this->currentUser = $this->security->getUser();
        }

        if ($this->currentUser !== null) {
            if (!$this->currentUser instanceof User) {
                throw new UnexpectedTypeException($this->currentUser, 'User');
            }
        } else {
            throw new NoUserException();
        }

        return $this->currentUser;
    }
}
