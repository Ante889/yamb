<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Game;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Game\Infrastructure\State\Processor\Create\CreateDuelGameProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'StartDuelGameData',
    operations: [
        new Post(
            uriTemplate: '/start-duel-game/{gameId}',
            uriVariables: [
                'gameId' => new Link(fromProperty: 'gameId')
            ],
            description: 'Start duel game',
            security: "is_granted('ROLE_USER')",
            processor: CreateDuelGameProcessor::class
        )
    ],
)]
final readonly class StartDuelGameResource
{
    public function __construct(
    )
    {
    }
}
