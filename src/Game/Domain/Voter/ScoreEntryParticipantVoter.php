<?php

namespace App\Game\Domain\Voter;

use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use App\User\Infrastructure\Security\AuthenticatedUserService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ScoreEntryParticipantVoter extends Voter
{
    public function __construct(
        private readonly AuthenticatedUserService           $userService,
        private readonly GameParticipantRepositoryInterface $gameParticipantRepository
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute == 'PARTICIPANT_ON_GAME';
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $gameParticipant = $this->gameParticipantRepository->findById((int)$subject);

        if (
            $gameParticipant &&
            $this->userService->getCurrentUser() === $gameParticipant->getUser()
        ) {
            return true;
        }

        return false;
    }
}
