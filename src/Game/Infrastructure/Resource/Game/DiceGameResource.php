<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Game;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Game\Infrastructure\State\Processor\ThrowDiceProcessor;

#[ApiResource(
    shortName: 'DiceGameData',
    operations: [
        new Post(
            uriTemplate: '/throw-dice/{gameParticipantId}',
            description: 'throw dice',
            security: "is_granted('ROLE_USER') and is_granted('PARTICIPANT_ON_GAME', request.attributes.get('gameParticipantId'))",
            processor: ThrowDiceProcessor::class
        ),
    ],
)]
final readonly class DiceGameResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?int  $gameParticipantId = null,
        public array $savedPositions = [],
    )
    {
    }
}
