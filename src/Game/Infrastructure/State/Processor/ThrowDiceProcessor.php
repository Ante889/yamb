<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\ThrowDiceCommand;
use App\Game\Application\Command\Game\ThrowDiceCommandHandler;
use App\Game\Infrastructure\Mapper\DiceResponseMapper;
use App\Game\Infrastructure\Resource\Game\DiceGameResource;
use Psr\Cache\InvalidArgumentException;
use Random\RandomException;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class ThrowDiceProcessor implements ProcessorInterface
{
    public function __construct(
        private ThrowDiceCommandHandler $throwDiceCommandHandler,
        private DiceResponseMapper      $diceResponseMapper
    )
    {
    }

    /**
     * @throws RandomException
     * @throws InvalidArgumentException
     */
    public function process(
        mixed     $data,
        Operation $operation,
        array     $uriVariables = [],
        array     $context = []
    ): JsonResponse
    {
        if (!$data instanceof DiceGameResource) {
            throw new \LogicException('Invalid input');
        }

        $command = new ThrowDiceCommand(
            (int)$uriVariables['gameParticipantId'],
            $data->savedPositions ?? []
        );

        $dice = $this->throwDiceCommandHandler->handle($command);

        return new JsonResponse(
            [
                'dices' => $this->diceResponseMapper->mapDice($dice['dices']),
                'throwNumber' => $dice['throwNumber']
            ],
            200
        );
    }
}
