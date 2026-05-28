<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Exception\ThrowDiceException;
use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use App\Game\Domain\Service\ThrowDiceService;
use Psr\Cache\InvalidArgumentException;
use Random\RandomException;

final readonly class ThrowDiceCommandHandler
{
    public function __construct(
        private TurnStateStoreInterface            $store,
        private ThrowDiceService                   $throwDiceService,
        private GameParticipantRepositoryInterface $gameParticipantRepository
    )
    {
    }

    /**
     * @throws RandomException
     * @throws InvalidArgumentException
     */
    public function handle(ThrowDiceCommand $command): array
    {
        $diceNumber = $this->gameParticipantRepository->getDiceNumberById($command->gameParticipantId);

        if (!$diceNumber) throw GameException::gameNotFound();

        $throwNumber = $this->store->getThrowNumberByParticipant($command->gameParticipantId);

        if ($throwNumber) $this->throwDiceService->assertCanThrow($throwNumber);

        $dice = $this->throwDiceService->rollDice(
            $diceNumber,
            $command->savedPositions,
            $state['dices'] ?? []
        );

        $this->store->setForParticipant(
            $command->gameParticipantId,
            [
                'throwNumber' => ($state['throwNumber'] ?? 0) + 1,
                'dices' => $this->store->getDicesByParticipant($command->gameParticipantId),
            ]
        );

        return [
            'throwNumber'=> ($state['throwNumber'] ?? 0) + 1,
            'dices' => $dice
        ];
    }
}
