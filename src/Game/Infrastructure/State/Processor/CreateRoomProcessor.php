<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Infrastructure\Resource\Game\RoomResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final readonly class CreateRoomProcessor implements ProcessorInterface
{
    public function __construct(
        // private CreateRoomCommandHandler $createRoomCommandHandler,
        private HubInterface $hub,
    ) {
    }

    public function process(
        mixed     $data,
        Operation $operation,
        array     $uriVariables = [],
        array     $context = []
    ): JsonResponse {
        if (!$data instanceof RoomResource) {
            throw new \LogicException('Invalid input');
        }

        // $room = $this->createRoomCommandHandler->handle(
        //     new CreateRoomCommand(
        //         numberOfDice: $data->numberOfDice,
        //         gameType: $data->gameType,
        //         isPublic: $data->isPublic,
        //     )
        // );

        // Objavi novu sobu svim pretplatnicima koji gledaju listu javnih soba.
        // Topic '/rooms' — frontend se pretplaćuje na EventSource('/rooms').
        // Ako je soba privatna, ne objavljuj na javnom topicu.
        if ($data->isPublic) {
            $this->hub->publish(new Update(
                topics: '/rooms',
                data: json_encode([
                    'type'        => 'ROOM_CREATED',
                    // 'code'        => $room->getCode(),
                    // 'gameType'    => $room->getGameType(),
                    // 'numberOfDice'=> $room->getNumberOfDice(),
                ]),
            ));
        }

        return new JsonResponse(
            [
                // 'code' => $room->getCode(),
            ],
            201
        );
    }
}
