<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Validator\GameValidator;
use App\Shared\Domain\Mercure\MercureServiceInterface;
use App\Shared\Domain\Mercure\MercureTopics;
use App\User\Infrastructure\Security\AuthenticatedUserService;

final readonly class StartNewDuelGameCommandHandler
{
    public function __construct(
        private GameRepositoryInterface  $gameRepository,
        private AuthenticatedUserService $userService,
        private GameValidator            $gameValidator,
        private MercureServiceInterface  $mercureService
    )
    {
    }

    public function handle(StartNewDuelGameCommand $command): void
    {
        $game = $this->gameRepository->findById($command->gameId);

        if (!$game) throw GameException::gameNotFound();

        //fali provjera je li game već startao
        $this->gameValidator->isPlayerInRoom($game, $this->userService->getCurrentUser());
        $this->gameValidator->checkNumberOfParticipants($game);

        $game->startDuelGame();

        $this->mercureService->create(
            MercureTopics::startedGame($game->getId()),
            [
                'id' => $game->getId(),
                'status' => 'started'
            ]
        );

        $this->gameRepository->save();

    }
}
