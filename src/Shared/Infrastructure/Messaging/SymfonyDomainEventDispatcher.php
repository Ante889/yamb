<?php

namespace App\Shared\Infrastructure\Messaging;

use App\Shared\Application\Service\DomainEventDispatcher;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyDomainEventDispatcher implements DomainEventDispatcher
{
    public function __construct(
        private MessageBusInterface $bus
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            $this->bus->dispatch($event);
        }
    }
}
