<?php

namespace App\Game\Application\Message;

use App\Game\Domain\Event\GameCanceled;
use App\Game\Domain\Service\GameCleanupService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GameCanceledHandler
{
    public function __construct(
        private GameCleanupService $gameCleanupService
    ) {}

    public function __invoke(GameCanceled $event): void
    {
       $this->gameCleanupService->cleanScoreEntry($event->gameId);
       $this->gameCleanupService->cleanParticipants($event->gameId);
       $this->gameCleanupService->cleanGame($event->gameId);
    }
}
