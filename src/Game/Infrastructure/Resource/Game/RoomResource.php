<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Game;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Game\Infrastructure\State\Processor\CreateRoomProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'RoomData',
    operations: [
        new Post(
            uriTemplate: '/rooms',
            description: 'Create a new room',
            security: "is_granted('ROLE_USER')",
            processor: CreateRoomProcessor::class
        ),
    ],
)]
final readonly class RoomResource
{
    public function __construct(
        #[Assert\NotBlank]
        public ?int    $numberOfDice = null,
        public ?string $gameType = null,
        public bool    $isPublic = true,
    ) {
    }
}
