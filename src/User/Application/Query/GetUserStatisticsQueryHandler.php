<?php

declare(strict_types=1);

namespace App\User\Application\Query;

use App\User\Domain\Repository\UserRepositoryInterface;

final readonly class GetUserStatisticsQueryHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function query(GetUserStatisticsQuery $query): array
    {
        return [
            'username' => $query->user->getUsername(),
            'duel' => $this->userRepository->getDuelStatisticByUser($query->user),
            'records' => $this->userRepository->getRecordsByUser($query->user)
        ];
    }
}
