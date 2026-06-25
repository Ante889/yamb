<?php

declare(strict_types=1);

namespace App\Shared\Domain\Paginator;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use App\Shared\Domain\Paginator\Value\Filter;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\HttpFoundation\Request;

final readonly class Paginator implements PaginatorInterface
{
    public function __construct(private Pagination $pagination)
    {
    }

    /**
     * @param array<string, mixed>|array{request?: Request, resource_class?: string} $context
     */
    public function getFilter(Operation $operation, array $context = []): Filter
    {
        return new Filter(
            $this->pagination->getLimit($operation, $context),
            $this->pagination->getOffset($operation, $context),
            $this->pagination->getPage($context),
        );
    }

    public static function queryPaginator(Query $queryBuilder): DoctrinePaginator
    {
        $paginator = new DoctrinePaginator($queryBuilder , $fetchJoinCollection = true);

        return $paginator->setUseOutputWalkers(false);
    }
}
