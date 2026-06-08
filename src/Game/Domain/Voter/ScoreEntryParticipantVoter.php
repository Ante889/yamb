<?php

namespace App\Game\Domain\Voter;

use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Security\AuthenticatedUserService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ScoreEntryParticipantVoter extends Voter
{
    public function __construct(
        private readonly AuthenticatedUserService           $userService,
        private readonly UserRepositoryInterface            $userRepository
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute == 'PARTICIPANT_ON_GAME';
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $this->userRepository->findByGameParticipantId((int)$subject);

        if (
            $user &&
            $this->userService->getCurrentUser() === $user
        ) {
            return true;
        }

        return false;
    }
}
