<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Game;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Game\Infrastructure\State\Processor\CreateRoomProcessor;
use App\Game\Infrastructure\State\Processor\JoinInRoomWithJoinCodeProcessor;
use App\Game\Infrastructure\State\Provider\PublicRoomsProvider;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
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
    ],
)]
final readonly class RoomResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?string $joinCode = null,
        #[Assert\NotBlank(groups: ['createRoom'])]
        public ?int    $numberOfDice = null,
        #[Assert\NotBlank(groups: ['createRoom'])]
        public ?string $gameType = null,
        public ?bool   $isPublic = false
    )
    {
    }
}
