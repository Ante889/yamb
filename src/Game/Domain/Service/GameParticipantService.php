<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Domain\Enum\GameParticipantStatusEnum;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Validator\JoinGameParticipantValidator;
use App\User\Domain\Model\User;

final readonly class GameParticipantService
{
    public function __construct(
        private JoinGameParticipantValidator $joinGameParticipantValidator,
        private GameRepositoryInterface      $gameRepository
    )
    {
    }

    public function gameParticipantJoinChecks(Game $game, User $user): void
    {
        $this->joinGameParticipantValidator->isThereSpaceInTheRoom($game);

        $this->joinGameParticipantValidator->isAlreadyPlayerAlreadyInRoom($game, $user);

        $this->joinGameParticipantValidator->gameAlreadyStarted($game);
    }

    public function addParticipant(Game $game, User $user): Game
    {
        $participant = GameParticipant::create(
            $user,
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
