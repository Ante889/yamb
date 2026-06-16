<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Model\Game;

final readonly class GameStatusService
{
    public function __construct(
    )
    {
    }

    public function isGameFinished(Game $game, int $numberOfInsertedFields): bool
    {
        $columns = $game->getGameType()->columns();

        $totalFields = count($columns) * count(ScoreEntryFieldEnum::cases());

        return $numberOfInsertedFields >= $totalFields;
    }

}
