<?php

declare(strict_types=1);

namespace App\Game\Application\EventListener;

use App\Game\Domain\Event\ChatCreatedEvent;
use App\Shared\Domain\Mercure\MercureServiceInterface;
use App\Shared\Domain\Mercure\MercureTopics;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final readonly class ChatCreatedListener
{
    public function __construct(
        private MercureServiceInterface $mercureService,
    ) {
    }

    public function __invoke(ChatCreatedEvent $event): void
    {
        $this->mercureService->create(
            MercureTopics::gameChat($event->gameId),
            [
                'id' => $event->chatId,
                'username' => $event->username,
                'text' => $event->text
            ]
        );
    }
}
