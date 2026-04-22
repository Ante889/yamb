<?php

declare(strict_types=1);

namespace App\Core\User;


use App\User\Domain\Model\User;

interface UserCoreServiceInterface
{
    public function getCurrentUser(): User;
}
