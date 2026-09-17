<?php

declare(strict_types=1);

namespace App\User\Application\Query;

use App\User\Domain\Model\User;

final readonly class GetUserStatisticsQuery
{
    public function __construct(
        public User $user
    )
    {
    }
}
