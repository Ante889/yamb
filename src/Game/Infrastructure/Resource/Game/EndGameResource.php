<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Game;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use App\Game\Infrastructure\State\Processor\EndSoloGameProcessor;
use App\Game\Infrastructure\State\Provider\GameParticipantProvider;

#[ApiResource(
    shortName: 'EndGameData',
    operations: [
        new Delete(
            uriTemplate: '/end-solo-game/{gameParticipantId}',
            description: 'End solo game',
            security: "is_granted('ROLE_USER') and is_granted('PARTICIPANT_ON_GAME', request.attributes.get('gameParticipantId'))",
            provider: GameParticipantProvider::class,
            processor: EndSoloGameProcessor::class
        )
    ],
)]
final readonly class EndGameResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?int $gameParticipantId = null
    )
    {
    }
}
