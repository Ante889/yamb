<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Game;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Game\Infrastructure\State\Processor\Create\CreateSoloGameProcessor;

#[ApiResource(
    shortName: 'StartGameData',
    operations: [
        new Post(
            uriTemplate: '/start-solo-game',
            description: 'Start solo game',
            security: "is_granted('ROLE_USER')",
            processor: CreateSoloGameProcessor::class
        )
    ],
)]
final readonly class StartGameResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?int $gameId = null
    )
    {
    }
}
