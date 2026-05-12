<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Response\Game;

use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;

final readonly class StartGameResponse
{
    public static function fromEntity(Game $game): array
    {
        return [
            'key' => $game->getId(),
            'numberOfDice' => $game->getDiceNumber(),
            'participants' => array_map(
                static fn (GameParticipant $participant): array => [
                    'id' => $participant->getId(),
                    'userId' => $participant->getUser()->getId(),
                    'status' => $participant->getStatus(),
                    'position' => $participant->getPosition(),
                ],
                $game->getParticipants()->toArray()
            ),
        ];
    }
}
