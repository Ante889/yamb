<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game\Announcement;


use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Validator\AnnouncementValidator;

final readonly class SetAnnouncementFieldCommandHandler
{
    public function __construct(
        private AnnouncementValidator   $announcementValidator,
        private TurnStateStoreInterface $store,
    )
    {
    }

    public function handle(SetAnnouncementFieldCommand $command): void
    {
        $gameParticipantId = $command->gameParticipantId;

        $this->announcementValidator->checkThrowNumber($gameParticipantId);
        $this->announcementValidator->isAlreadySet($gameParticipantId);

        $this->store->setAnnouncement($gameParticipantId, $command->row->value);
    }
}
