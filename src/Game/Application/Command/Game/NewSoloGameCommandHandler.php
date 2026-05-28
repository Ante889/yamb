<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Enum\GameParticipantStatusEnum;
use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Enum\GameTypeEnum;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Service\JoinCodeGenerator;
use App\User\Infrastructure\Security\AuthenticatedUserService;
use Random\RandomException;

final readonly class NewSoloGameCommandHandler
{
    public function __construct(
        private GameRepositoryInterface  $gameRepository,
        private JoinCodeGenerator        $codeGenerator,
        private AuthenticatedUserService $userService
    )
    {
    }

    /**
     * @throws RandomException
     */
    public function handle(NewSoloGameCommand $command): Game
    {
        $game = (new Game())
            ->setGameStatus(GameStatusEnum::IN_PROGRESS->value)
            ->setDiceNumber($command->numberOfDice)
            ->setMinPlayers(1)
            ->setMaxPlayers(1)
            ->setJoinCode($this->codeGenerator->generateUnique())
            ->setStartedAt(new \DateTimeImmutable())
            ->setGameType(GameTypeEnum::from($command->gameType));

        $participant = GameParticipant::createSolo(
            $this->userService->getCurrentUser(),
            $game,
        );

        $game->addParticipant($participant);

        $this->gameRepository
            ->add($game)
            ->save();

        return $game;
    }
}
