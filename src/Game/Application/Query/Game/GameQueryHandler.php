<?php

declare(strict_types=1);

namespace App\Game\Application\Query\Game;

use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Validator\GameValidator;
use App\User\Infrastructure\Security\AuthenticatedUserService;

final readonly class GameQueryHandler
{
    public function __construct(
        private GameRepositoryInterface  $gameRepository,
        private GameValidator            $gameValidator,
        private AuthenticatedUserService $userService
    )
    {
    }

    /**
     * @param GameQuery $command
     * @return string
     */
    public function query(GameQuery $command): Game
    {
        $game = $this->gameRepository->findById($command->gameId);

        if (!$command->gameId) {
            throw GameException::gameNotFound();
        }

        $this->gameValidator->isPlayerInRoom($game, $this->userService->getCurrentUser());

        return $game;
    }
}
