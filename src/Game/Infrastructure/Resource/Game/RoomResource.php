<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Game;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Game\Infrastructure\State\Processor\Create\CreateRoomProcessor;
use App\Game\Infrastructure\State\Processor\Join\JoinInRoomWithJoinCodeProcessor;
use App\Game\Infrastructure\State\Processor\Join\ManuallyJoinInRoomProcessor;
use App\Game\Infrastructure\State\Provider\PublicRoomsProvider;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'RoomData',
    operations: [
        new Get(
            uriTemplate: '/public-rooms',
            description: 'Get all public rooms',
            security: "is_granted('ROLE_USER')",
            provider: PublicRoomsProvider::class
        ),
        new Post(
            uriTemplate: '/rooms',
            description: 'Create a new room',
            security: "is_granted('ROLE_USER')",
            validationContext: ['groups' => ['createRoom']],
            processor: CreateRoomProcessor::class
        ),
        new Post(
            uriTemplate: '/join-room/{joinCode}',
            description: 'Join in room with join code',
            security: "is_granted('ROLE_USER')",
            processor: JoinInRoomWithJoinCodeProcessor::class
        ),
        new Post(
            uriTemplate: '/join-room-manually/{gameId}',
            uriVariables: [
                'gameId' => new Link(fromProperty: 'gameId')
            ],
            description: 'Join in room by game id',
            security: "is_granted('ROLE_USER')",
            processor: ManuallyJoinInRoomProcessor::class
        )
    ],
)]
final readonly class RoomResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?string $joinCode = null,
        public ?string $gameId = null,
        #[Assert\NotBlank(groups: ['createRoom'])]
        public ?int    $numberOfDice = null,
        #[Assert\NotBlank(groups: ['createRoom'])]
        public ?string $gameType = null,
        public ?bool   $isPublic = false
    )
    {
    }
}
