<?php

namespace App\User\Domain\Enum;

enum UserRoleEnum: string
{
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';
}
