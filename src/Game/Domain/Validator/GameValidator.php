<?php

declare(strict_types=1);

namespace App\Game\Domain\Validator;

use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Exception\GameParticipantException;
use App\Game\Domain\Model\Game;
use App\User\Domain\Model\User;

final readonly class GameValidator
{
    public function isPlayerInRoom(Game $game, User $user): void
    {
        $exist = false;

        foreach ($game->getParticipants() as $participant) {
            if ($user === $participant->getUser()) {
                $exist = true;
            }
        }

        if(!$exist) {
            throw GameException::gameNotFound();
        }
    }
}
