<?php

declare(strict_types=1);

namespace App\Game\Domain\Validator;

use App\Game\Domain\Contract\TurnStateStoreInterface;
use App\Game\Domain\Exception\AnnouncementException;

final readonly class AnnouncementValidator
{
    private const maxThrowNumber = 1;

    public function __construct(
        private TurnStateStoreInterface $store,
    )
    {
    }

    public function checkThrowNumber(int $participantId): void
    {
        if ($this->store->getThrowNumberByParticipant($participantId) > self::maxThrowNumber) {
            throw AnnouncementException::maxThrowNumberIsReached();
        }
    }

    public function isAlreadySet(int $participantId): void
    {
        if($this->store->getAnnouncement($participantId)) {
            throw AnnouncementException::alreadySet();
        }
    }
}
