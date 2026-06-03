<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Game\Application\Command\Game\Announcement\SetAnnouncementFieldCommand;
use App\Game\Application\Command\Game\Announcement\SetAnnouncementFieldCommandHandler;
use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Infrastructure\Resource\ScoreEntry\ScoreEntryResource;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class SetAnnouncementFieldProcessor implements ProcessorInterface
{
    public function __construct(
        private SetAnnouncementFieldCommandHandler $setAnnouncementFieldCommandHandler
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
        if (!$data instanceof ScoreEntryResource) {
            throw new \LogicException('Invalid input');
        }

        $command = new SetAnnouncementFieldCommand(
            gameParticipantId: (int)$uriVariables['gameParticipantId'],
            row: ScoreEntryFieldEnum::from($data->row)
        );

        $this->setAnnouncementFieldCommandHandler->handle($command);

        return new JsonResponse(
            [
                'row' => ScoreEntryFieldEnum::from($data->row)
            ],
            201
        );
    }
}
