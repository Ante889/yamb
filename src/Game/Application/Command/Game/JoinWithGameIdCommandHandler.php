<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Service\GameParticipantService;
use App\Game\Domain\Validator\GameValidator;
use App\User\Infrastructure\Security\AuthenticatedUserService;

final readonly class JoinWithGameIdCommandHandler
{
    public function __construct(
        private GameRepositoryInterface  $gameRepository,
        private AuthenticatedUserService $userService,
        private GameParticipantService   $gameParticipantService
    )
    {
    }

    public function handle(JoinWithGameIdCommand $command): Game
    {
        $game = $this->gameRepository->findById($command->gameId);

        if (!$game) throw GameException::gameNotFound();

        $this->gameParticipantService->gameParticipantJoinChecks($game, $this->userService->getCurrentUser());

        return $this->gameParticipantService->addParticipant($game, $this->userService->getCurrentUser());
    }
}
