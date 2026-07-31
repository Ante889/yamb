<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Game\Application\Dto\ScoreSheetResult;
use App\Game\Application\Query\ScoreEntry\GetScoreEntryByGameParticipantQuery;
use App\Game\Application\Query\ScoreEntry\GetScoreEntryByGameParticipantQueryHandler;
use Psr\Cache\InvalidArgumentException;

final readonly class ScoreEntryByGameParticipantProvider implements ProviderInterface
{
    public function __construct(
        private GetScoreEntryByGameParticipantQueryHandler $getScoreEntryByGameParticipantQueryHandler
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ScoreSheetResult
    {
        return $this->getScoreEntryByGameParticipantQueryHandler->query(
            new GetScoreEntryByGameParticipantQuery(
                gameParticipantId: $uriVariables['gameParticipantId']
            )
        );
    }
}
