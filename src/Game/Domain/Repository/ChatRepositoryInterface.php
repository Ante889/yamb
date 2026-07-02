<?php

declare(strict_types = 1);

namespace App\Game\Domain\Repository;

use App\Game\Domain\Model\Chat;

interface ChatRepositoryInterface
{

    /**
     * @param int $gameId
     * @return array<int, mixed>
     */
    public function getByGame(int $gameId): array;
    public function add(Chat $chat): self;
    public function remove(Chat $chat): void;
    public function save(): void;
}
