<?php

namespace App\Game\Application\Message;

use App\Game\Domain\Event\GameCanceled;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GameCanceledHandler
{
    public function __construct(
//        private GameCleanupService $cleanup
    ) {}

    public function __invoke(GameCanceled $event): void
    {
       dd('test');
    }
}
