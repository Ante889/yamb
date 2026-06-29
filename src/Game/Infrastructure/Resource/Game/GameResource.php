<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Game;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Game\Infrastructure\State\Provider\GameProvider;
use App\Game\Infrastructure\State\Provider\PublicRoomsProvider;

#[ApiResource(
    shortName: 'GameData',
    operations: [
        new Get(
            uriTemplate: '/game/{id}',
            description: 'Get game by id',
            security: "is_granted('ROLE_USER')",
            provider: GameProvider::class
        )
    ],
)]
final readonly class GameResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?string $id = null
    )
    {
    }
}
