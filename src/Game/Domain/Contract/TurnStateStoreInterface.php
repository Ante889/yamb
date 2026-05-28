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

    public function getDicesByParticipant(int $participantId);

    public function getThrowNumberByParticipant(int $participantId);

    public function deleteParticipant(int $participantId): void;
}
