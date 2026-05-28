<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\EnterNewEntryCommand;
use App\Game\Application\Command\Game\EnterNewEntryCommandHandler;
use App\Game\Application\Command\Game\ThrowDiceCommand;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Infrastructure\Resource\ScoreEntry\ScoreEntryResource;
use Psr\Cache\InvalidArgumentException;
use Random\RandomException;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class EnterNewEntryProcessor implements ProcessorInterface
{
    public function __construct(
        private EnterNewEntryCommandHandler $enterNewEntryCommandHandler
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
        if (!$data instanceof ScoreEntryResource) {
            throw new \LogicException('Invalid input');
        }

        $command = new EnterNewEntryCommand(
            gameParticipantId: (int)$uriVariables['gameParticipantId'],
            column: ScoreEntryColumnEnum::from($data->column),
            row: ScoreEntryFieldEnum::from($data->row)
        );

        $this->enterNewEntryCommandHandler->handle($command);

        return new JsonResponse(
            [
                'success' => true
            ],
            200
        );
    }
}
