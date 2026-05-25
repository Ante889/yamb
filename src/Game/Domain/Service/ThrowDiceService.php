<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Domain\Exception\ThrowDiceException;
use Random\RandomException;

final class ThrowDiceService
{
    private const MAX_THROWS = 3;
    private const DICE_MIN = 5;
    private const DICE_MAX = 6;


    public function validateDiceNumber(int $diceNumber): void
    {
        if ($diceNumber < self::DICE_MIN || $diceNumber > self::DICE_MAX) {
            throw ThrowDiceException::invalidDiceNumber();
        }
    }

    public function assertCanThrow(array $state): void
    {
        if (($state['throwNumber'] ?? 0) >= self::MAX_THROWS) {
            throw ThrowDiceException::maxThrowsReached();
        }
    }

    /**
     * @param int[] $savedPositions
     * @param int[] $previousDice
     * @return int[]
     * @throws RandomException
     */
    public function rollDice(
        int $diceNumber,
        array $savedPositions,
        array $previousDice
    ): array
    {
        $result = [];

        for ($i = 0; $i < $diceNumber; $i++) {
            if (
                !empty($savedPositions[$i]) &&
                isset($previousDice[$i])
            ) {
                $result[$i] = $previousDice[$i];
                continue;
            }

            $result[$i] = random_int(1, 6);
        }

        return $result;
    }
}
