<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Chat;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Game\Infrastructure\State\Processor\Create\CreateNewChatProcessor;
use App\Game\Infrastructure\State\Provider\GameChatProvider;

#[ApiResource(
    shortName: 'ChatData',
    operations: [
        new Get(
            uriTemplate: '/chat/{gameId}',
            uriVariables: [
                'gameId' => new Link(fromProperty: 'gameId')
            ],
            description: 'Get chat by game',
            security: "is_granted('ROLE_USER')",
            provider: GameChatProvider::class
        ),
        new Post(
            uriTemplate: '/chat/{gameParticipantId}',
            uriVariables: [
                'gameParticipantId' => new Link(fromProperty: 'gameParticipantId')
            ],
            description: 'Get create game chat',
            security: "is_granted('ROLE_USER')",
            processor: CreateNewChatProcessor::class
        ),
    ],
)]
final readonly class ChatResource
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $text = null
    )
    {
    }
}
