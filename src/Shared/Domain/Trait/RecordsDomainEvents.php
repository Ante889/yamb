<?php

namespace App\Shared\Domain\Trait;

trait RecordsDomainEvents
{
    private array $domainEvents = [];

    protected function recordThat(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
