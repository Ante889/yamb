<?php

declare(strict_types=1);

namespace App\Game\Domain\Service;

use App\Game\Domain\Model\Game;
use App\Game\Domain\Model\GameParticipant;
use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Game\Domain\Validator\JoinGameParticipantValidator;
use App\Game\Infrastructure\Mapper\GameResponseMapper;
use App\Shared\Domain\Mercure\MercureServiceInterface;
use App\Shared\Domain\Mercure\MercureTopics;
use App\User\Domain\Model\User;
use Psr\Cache\InvalidArgumentException;

final readonly class GameParticipantService
{
    public function __construct(
        private JoinGameParticipantValidator       $joinGameParticipantValidator,
        private GameRepositoryInterface            $gameRepository,
        private GameParticipantRepositoryInterface $gameParticipantRepository,
        private MercureServiceInterface            $mercureService,
        private GameResponseMapper                 $gameResponseMapper
    )
    {
    }

    public function gameParticipantJoinChecks(Game $game, User $user): void
    {
        $this->joinGameParticipantValidator->isThereSpaceInTheRoom($game);

        $this->joinGameParticipantValidator->isAlreadyPlayerAlreadyInRoom($game, $user);

        $this->joinGameParticipantValidator->gameAlreadyStarted($game);
    }

    /**
     * @param array $gameParticipants
     * @return void
     * @throws InvalidArgumentException
     */
    public function removeExpiredGameParticipants(array $gameParticipants): void
    {
        /** @var GameParticipant $gameParticipant */
        foreach ($gameParticipants as $gameParticipant) {
            if ($gameParticipant->getPosition() === 1) {
                $game = $gameParticipant->getGame();
                $this->gameParticipantRepository->deleteByGameId($game->getId());
                $this->gameRepository->remove($game);

                $this->mercureService->delete(
                    MercureTopics::rooms(),
                    [
                        'key' => $game->getId()
                    ]
                );
            } else {
                $gameMapper = $this->gameResponseMapper->fromEntity(
                    $gameParticipant->getGame()->removeParticipant($gameParticipant)
                );

                $this->mercureService->update(
                    MercureTopics::rooms(),
                    $gameMapper
                );
            }
        }

        $this->gameParticipantRepository->save();
    }

    public function removeEmptyGame(array $games): void
    {
        foreach ($games as $game) {
            $this->gameRepository->remove($game);

            $this->mercureService->delete(
                MercureTopics::rooms(),
                [
                    'key' => $game->getId()
                ]
            );
        }

        $this->gameParticipantRepository->save();
    }
}
