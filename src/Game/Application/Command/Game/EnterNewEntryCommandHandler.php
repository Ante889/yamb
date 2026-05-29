<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Model\ScoreEntry;
use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;
use App\Game\Domain\Service\CalculatePointsForEntryService;
use App\Game\Domain\Validator\ScoreEntryValidator;

final readonly class EnterNewEntryCommandHandler
{
    public function __construct(
        private TurnStateStoreInterface            $store,
        private CalculatePointsForEntryService     $calculatePointsForEntryService,
        private GameParticipantRepositoryInterface $gameParticipantRepository,
        private ScoreEntryRepositoryInterface      $scoreEntryRepository,
        private ScoreEntryValidator                $entryValidator
    )
    {
    }

    public function handle(EnterNewEntryCommand $command): void
    {
        $participantId = $command->gameParticipantId;

        $this->entryValidator->assertPositionFree($participantId, $command->column, $command->row);

        $point = $this->calculatePointsForEntryService->calculate(
            $this->store->getDicesByParticipant($participantId),
            $command->column,
            $command->row,
            $command->gameParticipantId
        );

        $scoreEntry = ScoreEntry::create(
            gameParticipant: $this->gameParticipantRepository->findById($participantId),
            field: $command->row,
            column: $command->column,
            value: $point
        );

        $this->scoreEntryRepository->add($scoreEntry)->save();
    }
}
