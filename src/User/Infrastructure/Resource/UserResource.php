<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\User\Infrastructure\State\Provider\CurrentUserDataProvider;

#[ApiResource(
    shortName: 'UserData',
    operations: [
        new Get(
            uriTemplate: '/user/me',
            description: 'Current user data',
            security: "is_granted('ROLE_USER')",
            provider: CurrentUserDataProvider::class
        )
    ],
)]
final readonly class UserResource
{
    public function __construct(
    ) {}
}
