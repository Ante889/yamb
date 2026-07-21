<?php

declare(strict_types=1);

namespace App\Game\Domain\Validator;

use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Exception\GameParticipantException;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;
use App\Shared\Infrastructure\Cache\CacheKeyGenerator;
use App\Shared\Infrastructure\Cache\RedisCacheService;
use App\User\Domain\Model\User;

final readonly class GameValidator
{
    public function __construct(
        private RedisCacheService $redisCacheService,
        private CacheKeyGenerator $cacheKeyGenerator
    )
    {
    }

    public function isPlayerInRoom(Game $game, User $user): void
    {
        $exist = false;

        foreach ($game->getParticipants() as $participant) {
            if ($user === $participant->getUser()) {
                $exist = true;
            }
        }

        if (!$exist) {
            throw GameParticipantException::gameParticipantNotFound();
        }
    }

    public function isDuelGameAlreadyStarted(Game $game): void
    {
        if ($game->getGameStatus() !== GameStatusEnum::LOBBY->value) {
            throw GameException::gameIsAlreadyStarted();
        }
    }

    public function checkNumberOfParticipants(Game $game, int $requiredParticipants = 2): void
    {
        if (count($game->getParticipants()) < $requiredParticipants) {
            throw GameException::notEnoughPlayers();
        }

        /** @var GameParticipant $participant */
        foreach ($game->getParticipants() as $participant) {
            $participantHeartBeat = $this->redisCacheService->get(
                $this->cacheKeyGenerator->forHeartBeat($participant->getId())
            );

            if(!$participantHeartBeat) {
                throw GameException::notEnoughPlayers();
            }
        }
    }
}
