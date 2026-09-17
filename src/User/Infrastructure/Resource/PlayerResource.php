<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\User\Infrastructure\State\Provider\PlayerStatisticsDataProvider;

#[ApiResource(
    shortName: 'UserData',
    operations: [
        new Get(
            uriTemplate: '/user/statistics/{id}',
            description: 'Current user data',
            security: "is_granted('ROLE_USER')",
            provider: PlayerStatisticsDataProvider::class
        )
    ],
)]
final readonly class PlayerResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?int $id = null
    ) {}
}
