<?php

declare(strict_types = 1);

namespace App\Game\Domain\Repository;

use App\Game\Domain\Model\ScoreSheet;

interface ScoreSheetRepositoryInterface
{
    public function add(ScoreSheet $scoreSheet): self;
    public function save(): void;
}
