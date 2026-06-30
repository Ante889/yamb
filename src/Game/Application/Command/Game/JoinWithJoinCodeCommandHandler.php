<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Enum\GameParticipantStatusEnum;
use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Service\GameParticipantService;
use App\User\Infrastructure\Security\AuthenticatedUserService;

final readonly class JoinWithJoinCodeCommandHandler
{
    public function __construct(
        private GameRepositoryInterface      $gameRepository,
        private AuthenticatedUserService     $userService,
        private GameParticipantService       $gameParticipantService
    )
    {
    }

    public function handle(JoinWithJoinCodeCommand $command): Game
    {
        $game = $this->gameRepository->findByJoinCode($command->joinCode);

        if (!$game) throw GameException::gameNotFound();

        $this->gameParticipantService->gameParticipantJoinChecks($game, $this->userService->getCurrentUser());

        $participant = GameParticipant::create(
            $this->userService->getCurrentUser(),
            $game,
            GameParticipantStatusEnum::NOT_READY,
            2
        );

        $game->addParticipant($participant);

        $this->gameRepository
            ->add($game)
            ->save();

        return $game;
    }
}
