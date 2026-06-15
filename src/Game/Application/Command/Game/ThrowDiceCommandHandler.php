<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Exception\GameException;
use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use App\Game\Domain\Service\ColumnService;
use App\Game\Domain\Service\ThrowDiceService;
use App\Game\Domain\Validator\ScoreEntryValidator;
use Psr\Cache\InvalidArgumentException;
use Random\RandomException;

final readonly class ThrowDiceCommandHandler
{
    public function __construct(
        private TurnStateStoreInterface            $store,
        private ThrowDiceService                   $throwDiceService,
        private ColumnService                      $columnService,
        private GameParticipantRepositoryInterface $gameParticipantRepository,
        private ScoreEntryValidator                $scoreEntryValidator
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

        $this->scoreEntryValidator->remainingColumns(
            $throwNumber,
            $this->columnService->getRemainingColumns($command->gameParticipantId),
            $this->store->getAnnouncement($command->gameParticipantId)
        );

        $dice = $this->throwDiceService->rollDice(
            $diceNumber,
            $command->savedPositions,
            $this->store->getDicesByParticipant($command->gameParticipantId)
        );

        $this->store->setForParticipant(
            $command->gameParticipantId,
            [
                'throwNumber' => $throwNumber + 1,
                'dices' => $dice,
            ]
        );

        return [
            'throwNumber' => $throwNumber + 1,
            'dices' => $dice
        ];
    }
}
