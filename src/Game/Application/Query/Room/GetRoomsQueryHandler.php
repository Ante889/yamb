<?php

declare(strict_types=1);

namespace App\Game\Application\Query\Room;


use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use App\Game\Domain\Repository\GameRepositoryInterface;

final readonly class GetRoomsQueryHandler
{
    public function __construct(
        private GameRepositoryInterface $gameRepository
    )
    {
    }

    /**
     * @param GetRoomsQuery $command
     * @return array<int, mixed>
     */
    public function query(GetRoomsQuery $command): array
    {
        return $this->gameRepository->getPublic();
    }
}
