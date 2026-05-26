<?php

declare(strict_types = 1);

namespace App\Game\Domain\Repository;

use App\Game\Domain\Model\Game;

interface GameRepositoryInterface
{
    public function gameByGameParticipant(int $gameParticipantId): ?Game;
    public function existsByJoinCode(string $joinCode): bool;
    public function add(Game $game): self;
    public function save(): void;
}
