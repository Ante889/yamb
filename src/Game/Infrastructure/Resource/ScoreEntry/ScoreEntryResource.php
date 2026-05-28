<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\ScoreEntry;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Game\Application\Dto\ScoreSheetResult;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Infrastructure\State\Processor\EndSoloGameProcessor;
use App\Game\Infrastructure\State\Processor\EnterNewEntryProcessor;
use Symfony\Component\Validator\Constraints as Assert;
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
        ),
        new Post(
            uriTemplate: '/enter-new-entry/{gameParticipantId}',
            description: 'Enter a new entry',
            security: "is_granted('ROLE_USER') and is_granted('PARTICIPANT_ON_GAME', request.attributes.get('gameParticipantId'))",
            processor: EnterNewEntryProcessor::class
        ),
    ],
)]
final readonly class ScoreEntryResource
{
    public function __construct(
        #[ApiProperty(readable: true, writable: false, identifier: true)]
        public ?int $gameParticipantId = null,
        #[Assert\Choice(callback: [ScoreEntryColumnEnum::class, 'values'])]
        public ?string $column = null,
        #[Assert\Choice(callback: [ScoreEntryFieldEnum::class, 'values'])]
        public ?string $row = null
    )
    {
    }
}
