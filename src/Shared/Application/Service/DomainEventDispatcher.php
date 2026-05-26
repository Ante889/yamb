<?php

declare(strict_types = 1);

namespace App\Shared\Application\Service;

interface DomainEventDispatcher
{
    public function dispatch(array $events): void;
}
