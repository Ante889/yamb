<?php

declare(strict_types=1);

namespace App\Game\Application\Query\Chat;

use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Repository\ChatRepositoryInterface;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Validator\GameValidator;
use App\User\Infrastructure\Security\AuthenticatedUserService;

final readonly class GetChatByGameQueryHandler
{
    public function __construct(
        private GameRepositoryInterface  $gameRepository,
        private ChatRepositoryInterface  $chatRepository,
        private AuthenticatedUserService $userService,
        private GameValidator            $gameValidator,
    )
    {
    }

    /**
     * @param int $gameId
     * @return array<int, mixed>
     */
    public function query(GetChatByGameQuery $command): array
    {
        $game = $this->gameRepository->findById($command->gameId);

        if (!$game) {
            throw GameException::gameNotFound();
        }

        $this->gameValidator->isPlayerInRoom($game, $this->userService->getCurrentUser());

        return $this->chatRepository->getByGame($command->gameId);
    }
}
