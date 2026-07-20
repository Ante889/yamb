<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor\Create;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\Create\NewChatCommand;
use App\Game\Application\Command\Game\Create\NewChatCommandHandler;
use App\Game\Infrastructure\Resource\Chat\ChatResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class CreateNewChatProcessor implements ProcessorInterface
{
    public function __construct(
        private NewChatCommandHandler $newChatCommandHandler,
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
        if (!$data instanceof ChatResource) {
            throw new \LogicException('Invalid data');
        }

        $this->newChatCommandHandler->handle(
            new NewChatCommand(
                gameParticipantId: (int)$uriVariables['gameParticipantId'],
                text: $data->text ?? ''
            )
        );

        return new JsonResponse(
            ['status' => 'success'],
            Response::HTTP_ACCEPTED
        );
    }
}
