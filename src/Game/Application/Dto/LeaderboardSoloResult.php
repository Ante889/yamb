<?php

declare(strict_types=1);

namespace App\Game\Application\Dto;

final readonly class LeaderboardSoloResult
{
    public function __construct(
        public string $username,
        public int $points
    ) {}

    public static function fromArray(array $data): array
    {
        return array_map(
            fn(array $item) => new self(
                username: $item['username'] ?? '',
                points: isset($item['points']) ? (int) $item['points'] : 0,
            ),
            $data
        );
    }
}
