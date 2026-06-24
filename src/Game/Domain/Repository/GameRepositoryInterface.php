<?php

declare(strict_types = 1);

namespace App\Game\Domain\Repository;

use App\Game\Domain\Model\Game;

interface GameRepositoryInterface
{
    public function deleteById(int $gameId): int;

    /**
     * @param string $type
     * @param int $dice
     * @return array<int, Game>
     */
    public function getFirstFiftyByType(string $type, int $dice): array;
    public function gameByGameParticipant(int $gameParticipantId): ?Game;
    public function findByJoinCode(string $joinCode): ?Game;
    public function existsByJoinCode(string $joinCode): bool;
    public function add(Game $game): self;
    public function save(): void;
}
