<?php

declare(strict_types=1);

namespace App\Game\Infrastructure\Console;

use App\Game\Application\Query\GameParticipant\AllParticipantsWithExpiredHeartBeatQueryHandler;
use App\Game\Domain\Service\GameParticipantService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

#[AsCommand(
    name: 'clear:rooms',
    description: "Clear public and private rooms"
)]
final class ClearRooms extends Command
{
    public function __construct(
        private readonly AllParticipantsWithExpiredHeartBeatQueryHandler $allParticipantsWithExpiredHeartBeatQueryHandler,
        private readonly GameParticipantService                          $gameParticipantService
    )
    {
        parent::__construct();
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws InvalidArgumentException
     */
    protected function execute(
        InputInterface  $input,
        OutputInterface $output
    ): int
    {
        $data = $this->allParticipantsWithExpiredHeartBeatQueryHandler->query();

        $this->gameParticipantService->removeExpiredGameParticipants(
            $data->expiredParticipants
        );

        $this->gameParticipantService->removeEmptyGame(
            $data->emptyGames
        );

        return Command::SUCCESS;
    }
}
