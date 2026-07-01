<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Validator\GameValidator;
use App\User\Infrastructure\Security\AuthenticatedUserService;

final readonly class StartNewDuelGameCommandHandler
{
    public function __construct(
        private GameRepositoryInterface  $gameRepository,
        private AuthenticatedUserService $userService,
        private GameValidator            $gameValidator
    )
    {
    }

    public function handle(StartNewDuelGameCommand $command): int
    {
        $game = $this->gameRepository->findById($command->gameId);

        if (!$game) throw GameException::gameNotFound();

        $this->gameValidator->isPlayerInRoom($game, $this->userService->getCurrentUser());
        $this->gameValidator->checkNumberOfParticipants($game);

        $game
            ->setGameStatus(GameStatusEnum::IN_PROGRESS->value)
            ->setStartedAt(new \DateTimeImmutable());

    }
}
