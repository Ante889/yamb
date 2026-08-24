<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game\Announcement;


use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Validator\AnnouncementValidator;
use App\Game\Domain\Validator\ScoreEntryValidator;
use App\Shared\Domain\Mercure\MercureServiceInterface;
use App\Shared\Domain\Mercure\MercureTopics;

final readonly class SetAnnouncementFieldCommandHandler
{
    public function __construct(
        private AnnouncementValidator   $announcementValidator,
        private TurnStateStoreInterface $store,
        private ScoreEntryValidator     $entryValidator,
        private MercureServiceInterface $mercureService
    )
    {
    }

    public function handle(SetAnnouncementFieldCommand $command): void
    {
        $gameParticipantId = $command->gameParticipantId;

        $this->entryValidator->assertPositionFree($command->gameParticipantId, ScoreEntryColumnEnum::ANNOUNCEMENT, $command->row);
        $this->announcementValidator->checkThrowNumber($gameParticipantId);
        $this->announcementValidator->isAlreadySet($gameParticipantId);

        $this->store->setAnnouncement($gameParticipantId, $command->row->value);

        $this->mercureService->create(
            MercureTopics::gameParticipantAnnouncementField($gameParticipantId),
            [
                'key' => $gameParticipantId,
                'row' => $command->row->value
            ]
        );
    }
}
