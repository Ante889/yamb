<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\FinishSoloGameCommand;
use App\Game\Application\Command\Game\FinishSoloGameCommandHandler;
use App\Game\Infrastructure\Resource\Game\EndGameResource;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class FinishSoloGameProcessor implements ProcessorInterface
{
    public function __construct(
        private FinishSoloGameCommandHandler $finishSoloGameCommandHandler,
    )
    {
    }

    public function process(
        mixed     $data,
        Operation $operation,
        array     $uriVariables = [],
        array     $context = []
    ): JsonResponse
    {
        if (!$data instanceof EndGameResource) {
            throw new \LogicException('Invalid data');
        }

        $this->finishSoloGameCommandHandler->handle(
            new FinishSoloGameCommand(
                gameParticipantId: $uriVariables['gameParticipantId']
            )
        );

        return new JsonResponse(
            ['status' => 'success'],
            202
        );
    }
}
