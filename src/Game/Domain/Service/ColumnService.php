<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Model\ScoreEntry;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;

final readonly class ColumnService
{
    public function __construct(
        private ScoreEntryRepositoryInterface $scoreEntryRepository
    )
    {
    }

    public function getRemainingColumns(int $gameParticipantId): array
    {
        $scoreEntries = $this->scoreEntryRepository->getByGameParticipantId($gameParticipantId);

        $requiredFieldCount = count(ScoreEntryFieldEnum::cases());

        $entriesByColumn = [];

        /** @var ScoreEntry $scoreEntry */
        foreach ($scoreEntries as $scoreEntry) {
            $column = $scoreEntry->getEntryColumn();

            $entriesByColumn[$column->value] ??= 0;
            ++$entriesByColumn[$column->value];
        }

        return array_values(
            array_filter(
                ScoreEntryColumnEnum::cases(),
                static fn (ScoreEntryColumnEnum $column): bool =>
                    ($entriesByColumn[$column->value] ?? 0) < $requiredFieldCount
            )
        );
    }
}
