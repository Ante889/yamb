<?php

declare(strict_types=1);

namespace App\Game\Application\Dto;

use App\Game\Domain\Model\GameParticipant;

final readonly class GameParticipantResult
{
    public function __construct(
        public int    $userId,
        public int    $participantId,
        public string $username,
        public string $moveStatus
    )
    {
    }

    public static function fillData(GameParticipant $participant): self
    {
        return new self(
            $participant->getUser()->getId(),
            $participant->getId(),
            $participant->getUser()->getUsername(),
            $participant->getStatus()->value
        );
    }
}
