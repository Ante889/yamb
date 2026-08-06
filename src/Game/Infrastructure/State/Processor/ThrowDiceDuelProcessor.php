<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\ThrowDiceCommand;
use App\Game\Application\Command\Game\ThrowDiceCommandHandler;
use App\Game\Infrastructure\Mapper\DiceResponseMapper;
use App\Game\Infrastructure\Resource\Game\DiceGameResource;
use App\Shared\Domain\Mercure\MercureServiceInterface;
use App\Shared\Domain\Mercure\MercureTopics;
use Psr\Cache\InvalidArgumentException;
use Random\RandomException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class ThrowDiceDuelProcessor implements ProcessorInterface
{
    public function __construct(
        private ThrowDiceCommandHandler $throwDiceCommandHandler,
        private DiceResponseMapper      $diceResponseMapper,
        private MercureServiceInterface $mercureService
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
        if (!$data instanceof DiceGameResource) throw new \LogicException('Invalid input');

        $command = new ThrowDiceCommand(
            (int)$uriVariables['gameParticipantId'],
            $data->savedPositions ?? []
        );

        $dice = $this->throwDiceCommandHandler->handle($command);

        $dices = $this->diceResponseMapper->mapDice($dice['dices']);
        $throwNumber = $dice['throwNumber'];

        $this->mercureService->create(
            MercureTopics::gameParticipantDice((int)$uriVariables['gameParticipantId']),
            [
                'id' => (int)$uriVariables['gameParticipantId'],
                'dices' => $dices,
                'throwNumber' => $throwNumber
            ]
        );

        return new JsonResponse(
            [
                'id' => (int)$uriVariables['gameParticipantId'],
                'dices' => $this->diceResponseMapper->mapDice($dice['dices']),
                'throwNumber' => $dice['throwNumber']
            ],
            Response::HTTP_OK
        );
    }
}
