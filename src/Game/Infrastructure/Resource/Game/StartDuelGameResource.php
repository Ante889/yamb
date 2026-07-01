<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Resource\Game;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Game\Infrastructure\State\Processor\Create\CreateDuelGameProcessor;
use App\Game\Infrastructure\State\Processor\Create\CreateSoloGameProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'StartDuelGameData',
    operations: [
        new Post(
            uriTemplate: '/start-duel-game',
            description: 'Start duel game',
            security: "is_granted('ROLE_USER')",
            processor: CreateDuelGameProcessor::class
        )
    ],
)]
final readonly class StartDuelGameResource
{
    public function __construct(
        #[Assert\NotBlank]
        public ?int    $numberOfDice = null,
        public ?string $gameType = null,
    )
    {
    }
}
