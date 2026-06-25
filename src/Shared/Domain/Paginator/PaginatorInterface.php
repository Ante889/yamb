<?php

declare(strict_types=1);

namespace App\Shared\Domain\Paginator;

use ApiPlatform\Metadata\Operation;
use App\Shared\Domain\Paginator\Value\Filter;
use Symfony\Component\HttpFoundation\Request;

interface PaginatorInterface
{
    /**
     * @param array<string, mixed>|array{request?: Request, resource_class?: string} $context
     */
    public function getFilter(Operation $operation, array $context = []): Filter;
}
