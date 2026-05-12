<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Domain\Exception\CreateSoloGameException;
use App\Game\Domain\Repository\GameRepositoryInterface;
use Random\RandomException;

final readonly class JoinCodeGenerator
{
    private const LENGTH = 10;
    private const ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public function __construct(
        private GameRepositoryInterface $gameRepository
    ) {
    }

    public function generateUnique(): string
    {
        try {
            do {
                $code = $this->generate();
            } while ($this->gameRepository->existsByJoinCode($code));

            return $code;
        } catch (RandomException $e) {
            throw CreateSoloGameException::joinCode();
        }
    }

    /**
     * @throws RandomException
     */
    private function generate(): string
    {
        $code = '';

        $maxIndex = strlen(self::ALPHABET) - 1;

        for ($i = 0; $i < self::LENGTH; $i++) {
            $code .= self::ALPHABET[random_int(0, $maxIndex)];
        }

        return $code;
    }
}
