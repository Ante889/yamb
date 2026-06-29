<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class HeartBeatProcessor implements ProcessorInterface
{
    public function __construct(
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function process(
        mixed     $data,
        Operation $operation,
        array     $uriVariables = [],
        array     $context = []
    ): JsonResponse
    {
//        if (!$data instanceof GameParticipantResource) {
//            throw new \LogicException('Invalid data');
//        }
//
//        $game = $this->joinCodeCommandHandler->handle(
//            new JoinWithJoinCodeCommand(
//                joinCode: $uriVariables['joinCode']
//            )
//        );
//
//        $gameMapper = $this->gameResponseMapper->fromEntity(
//            $game
//        );
//
//        $this->mercureService->update(
//            MercureTopics::rooms(),
//            $gameMapper
//        );
//
//        return new JsonResponse(
//            $gameMapper,
//            201
//        );
    }
}
