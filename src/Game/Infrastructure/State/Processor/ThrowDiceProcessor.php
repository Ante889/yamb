<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Shared\Domain\Cache\RedisCacheServiceInterface;
use Random\RandomException;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class ThrowDiceProcessor implements ProcessorInterface
{
    public function __construct(
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): JsonResponse {
        $gameParticipantId = (int) ($uriVariables['gameParticipantId'] ?? 0);
    }
}
