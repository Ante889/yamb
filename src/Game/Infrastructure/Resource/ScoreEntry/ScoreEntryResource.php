<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\ScoreEntry;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Game\Application\Dto\ScoreSheetResult;
use App\Game\Infrastructure\State\Provider\ScoreEntryByGameParticipantProvider;

#[ApiResource(
    shortName: 'ScoreEntryData',
    operations: [
        new Get(
            uriTemplate: '/score-entry-by-participant/{gameParticipantId}',
            description: 'Get score entry data by game participant id',
            security: "is_granted('ROLE_USER') and is_granted('PARTICIPANT_ON_GAME', request.attributes.get('gameParticipantId'))",
            output: ScoreSheetResult::class,
            provider: ScoreEntryByGameParticipantProvider::class
        )
    ],
)]
final readonly class ScoreEntryResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?int $gameParticipantId = null
    )
    {
    }
}
