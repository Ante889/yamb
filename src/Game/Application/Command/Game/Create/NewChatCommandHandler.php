<?php

declare(strict_types=1);

namespace App\Game\Application\Command\Game\Create;

use App\Game\Domain\Event\ChatCreatedEvent;
use App\Game\Domain\Exception\GameParticipantException;
use App\Game\Domain\Model\Chat;
use App\Game\Domain\Repository\ChatRepositoryInterface;
use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use App\User\Infrastructure\Security\AuthenticatedUserService;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class NewChatCommandHandler
{
    public function __construct(
        private GameParticipantRepositoryInterface $gameParticipantRepository,
        private ChatRepositoryInterface $chatRepository,
        private AuthenticatedUserService $userService,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function handle(NewChatCommand $command): void
    {
        $user = $this->userService->getCurrentUser();

        $gameParticipant = $this->gameParticipantRepository->getByIdAndUserId(
            $command->gameParticipantId,
            $user->getId(),
        );

        if ($gameParticipant === null) {
            GameParticipantException::gameParticipantNotFound();
        }

        $chat = Chat::create(
            $gameParticipant,
            $command->text,
        );

        $this->chatRepository
            ->add($chat)
            ->save();

        $this->eventDispatcher->dispatch(
            new ChatCreatedEvent(
                chatId: $chat->getId(),
                gameId: $gameParticipant->getGame()->getId(),
                username: $user->getUsername(),
                text: $chat->getText(),
            )
        );
    }
}
