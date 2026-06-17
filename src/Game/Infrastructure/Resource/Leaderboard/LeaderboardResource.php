<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Leaderboard;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Game\Application\Dto\LeaderboardSoloResult;
use App\Game\Infrastructure\State\Provider\LeaderboardSoloProvider;

#[ApiResource(
    shortName: 'LeaderboardData',
    operations: [
        new Get(
            uriTemplate: '/solo/leaderboard/{type}/{dice}',
            description: 'Get solo leaderboard',
            security: "is_granted('ROLE_USER')",
            output: LeaderboardSoloResult::class,
            provider: LeaderboardSoloProvider::class
        ),
    ],
)]
final readonly class LeaderboardResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?string $type = null,
        public ?int    $dice = null
    )
    {
    }
}
