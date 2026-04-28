<?php

declare(strict_types = 1);

namespace App\Game\Domain\Repository;

use App\Game\Domain\Model\Game;

interface GameRepositoryInterface
{
    public function add(Game $game): self;
    public function save(): void;
}
