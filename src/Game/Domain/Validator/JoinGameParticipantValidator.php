<?php

declare(strict_types=1);

namespace App\Game\Domain\Validator;

use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Exception\GameParticipantException;
use App\Game\Domain\Model\Game;
use App\User\Domain\Model\User;

final readonly class JoinGameParticipantValidator
{
    public function isThereSpaceInTheRoom(Game $game): void
    {
        if ($game->getMaxPlayers() <= count($game->getParticipants())) {
            throw GameException::gameIsFull();
        }
    }

    public function isAlreadyPlayerAlreadyInRoom(Game $game, User $user): void
    {
        foreach ($game->getParticipants() as $participant) {
            if ($user === $participant->getUser()) {
                throw GameParticipantException::gameParticipantIsAlreadyInRoom();
            }
        }
    }

    public function gameAlreadyStarted(Game $game): void
    {
        if($game->getGameStatus() !== GameStatusEnum::LOBBY->value) {
            throw GameException::gameIsAlreadyStarted();
        }
    }
}
