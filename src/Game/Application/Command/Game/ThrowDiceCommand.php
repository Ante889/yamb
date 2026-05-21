<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

final readonly class ThrowDiceCommand
{
    /**
     * @param int[] $savedPositions (0-5)
     */
    public function __construct(
        public int $gameParticipantId,
        public array $savedPositions,
    ) {
    }
}
