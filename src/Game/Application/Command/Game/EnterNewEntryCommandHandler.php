<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game;

use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Enum\GameParticipantStatusEnum;
use App\Game\Domain\Model\GameParticipant;
use App\Game\Domain\Model\ScoreEntry;
use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;
use App\Game\Domain\Service\CalculatePointsForEntryService;
use App\Game\Domain\Validator\ScoreEntryValidator;
use App\Shared\Domain\Mercure\MercureServiceInterface;

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

    public function handle(EnterNewEntryCommand $command): int
    {
        $participantId = $command->gameParticipantId;

        $this->entryValidator->assertPositionFree($participantId, $command->column, $command->row);

        $this->entryValidator->neverThrow($this->store->getThrowNumberByParticipant($participantId));

        $gameParticipant = $this->gameParticipantRepository->findById($participantId);

        $point = $this->calculatePointsForEntryService->calculate(
            $this->store->getDicesByParticipant($participantId),
            $command->column,
            $command->row,
            $gameParticipant
        );

        $scoreEntry = ScoreEntry::create(
            gameParticipant: $gameParticipant,
            field: $command->row,
            column: $command->column,
            value: $point
        );

        if ($command->isDuel) {
            $command->game->changePlayerTurn($gameParticipant);
        }

        $this->scoreEntryRepository->add($scoreEntry)->save();

        $this->store->deleteParticipant($participantId);

        return $point;
    }
}
