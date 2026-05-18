<?php

declare(strict_types = 1);

namespace App\Game\Domain\Repository;

use App\Game\Domain\Model\GameParticipant;

interface GameParticipantRepositoryInterface
{
    public function findById(int $id): ?GameParticipant;
    public function add(GameParticipant $gameParticipant): self;
    public function save(): void;
}
