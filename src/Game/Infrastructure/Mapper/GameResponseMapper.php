<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Mapper;

use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;
use App\Game\Infrastructure\Service\GameTurnStateService;
use Psr\Cache\InvalidArgumentException;

final readonly class GameResponseMapper
{
    public function __construct(
        private GameTurnStateService $gameTurnStateService
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function fromEntity(Game $game): array
    {
        return [
            'key' => $game->getId(),
            'numberOfDice' => $game->getDiceNumber(),
            'participants' => array_map(
                fn(GameParticipant $participant): array => [
                    'id' => $participant->getId(),
                    'userId' => $participant->getUser()->getId(),
                    'status' => $participant->getStatus(),
                    'position' => $participant->getPosition(),
                    'diceData' => $this->gameTurnStateService->getForParticipant(
                        $participant->getId()
                    ),
                ],
                $game->getParticipants()->toArray()
            ),
        ];
    }
}
