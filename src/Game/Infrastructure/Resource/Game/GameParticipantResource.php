<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Game;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Game\Infrastructure\State\Provider\GameProvider;

#[ApiResource(
    shortName: 'GameParticipantData',
    operations: [
        new Post(
            uriTemplate: '/game-parcitipant-heart-beat/{gameParticipantId}',
            description: 'Heart beat in lobby',
            security: "is_granted('ROLE_USER')",
            processor: GameProvider::class
        ),
    ],
)]
final readonly class GameParticipantResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?string $gameParticipantId = null
    )
    {
    }
}
