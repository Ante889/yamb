<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Mapper;

final readonly class DiceResponseMapper
{
    public function mapDice(array $dice): array
    {
        $labels = ['first', 'second', 'third', 'fourth', 'five', 'six'];

        $result = [];

        foreach ($dice as $i => $value) {
            if (!array_key_exists($i, $labels)) {
                continue;
            }

            $result[$labels[$i]] = $value;
        }

        return $result;
    }
}
