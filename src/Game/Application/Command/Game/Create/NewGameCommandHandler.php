<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game\Create;

use App\Game\Domain\Enum\GameParticipantStatusEnum;
use App\Game\Domain\Enum\GameTypeEnum;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Service\JoinCodeGenerator;
use App\User\Infrastructure\Security\AuthenticatedUserService;

final readonly class NewGameCommandHandler
{
    public function __construct(
        private GameRepositoryInterface  $gameRepository,
        private JoinCodeGenerator        $codeGenerator,
        private AuthenticatedUserService $userService
    )
    {
    }

    public function handle(NewGameCommand $command): Game
    {
        $game = (new Game())
            ->setGameStatus($command->gameStatus)
            ->setDiceNumber($command->numberOfDice)
            ->setMinPlayers($command->minPlayer)
            ->setMaxPlayers($command->maxPlayer)
            ->setJoinCode($this->codeGenerator->generateUnique())
            ->setStartedAt(new \DateTimeImmutable())
            ->setGameType(GameTypeEnum::from($command->gameType))
            ->setIsPublic($command->isPublic);

        $participant = GameParticipant::create(
            $this->userService->getCurrentUser(),
            $game,
            $command->isSolo ? GameParticipantStatusEnum::ACTIVE_TURN : GameParticipantStatusEnum::NOT_READY
        );

        $game->addParticipant($participant);

        $this->gameRepository
            ->add($game)
            ->save();

        return $game;
    }
}
