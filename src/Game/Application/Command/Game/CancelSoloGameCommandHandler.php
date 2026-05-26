<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Shared\Application\Service\DomainEventDispatcher;

final readonly class CancelSoloGameCommandHandler
{
    public function __construct(
        private GameRepositoryInterface $gameRepository,
        private DomainEventDispatcher   $eventDispatcher
    )
    {
    }

    public function handle(CancelSoloGameCommand $command): void
    {
        $game = $this->gameRepository->gameByGameParticipant($command->gameParticipantId);

        if (!$game) throw GameException::gameNotFound();

        $game->cancel();

        $this->gameRepository->save();

        $this->eventDispatcher->dispatch(
            $game->releaseEvents()
        );
    }
}
