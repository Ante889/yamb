<?php

declare(strict_types = 1);

namespace App\Game\Domain\Repository;

use App\Game\Domain\Model\GameParticipant;

interface GameParticipantRepositoryInterface
{
    public function getByIdAndUserId(int $id, int $userId): ?GameParticipant;
    public function getDiceNumberById(int $id): ?int;
    public function findById(int $id): ?GameParticipant;
    public function deleteByGameId(int $gameId);
    public function add(GameParticipant $gameParticipant): self;
    public function remove(GameParticipant $gameParticipant): self;
    public function save(): void;
}
