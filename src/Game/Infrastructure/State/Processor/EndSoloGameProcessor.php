<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\CancelSoloGameCommand;
use App\Game\Application\Command\Game\CancelSoloGameCommandHandler;
use App\Game\Infrastructure\Resource\Game\EndGameResource;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class EndSoloGameProcessor implements ProcessorInterface
{
    public function __construct(
        private CancelSoloGameCommandHandler $cancelSoloGameCommandHandler,
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

        $this->cancelSoloGameCommandHandler->handle(
            new CancelSoloGameCommand(
                gameParticipantId: $uriVariables['gameParticipantId']
            )
        );

        return new JsonResponse(
            ['status' => 'success'],
            202
        );
    }
}
