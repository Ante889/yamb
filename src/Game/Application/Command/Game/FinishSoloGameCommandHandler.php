<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Model\Game;
use App\Game\Domain\Repository\GameRepositoryInterface;

final readonly class FinishSoloGameCommandHandler
{
    public function __construct(
        private GameRepositoryInterface $gameRepository,
    )
    {
    }

    public function handle(FinishSoloGameCommand $command): Game
    {
        $game = $this->gameRepository->gameByGameParticipant($command->gameParticipantId);

        if (!$game) {
            throw GameException::gameNotFound();
        }

        $game->setGameStatus(GameStatusEnum::FINISHED->value);

        $this->gameRepository->save();

        return $game;
    }
}
