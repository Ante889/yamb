<?php

declare(strict_types = 1);

namespace App\Game\Domain\Contract;

use Psr\Cache\InvalidArgumentException;

interface TurnStateStoreInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getForParticipant(int $participantId): ?array;

    /**
     * @throws InvalidArgumentException
     */
    public function setForParticipant(int $participantId, mixed $data): void;

    public function getDicesByParticipant(int $participantId): array;

    public function getThrowNumberByParticipant(int $participantId) : int;

    public function setAnnouncement(int $participantId, string $row): void;

    public function getAnnouncement(int $participantId): ?string;

    public function deleteParticipant(int $participantId): void;
}
