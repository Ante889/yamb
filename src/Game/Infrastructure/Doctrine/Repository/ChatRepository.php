<?php

namespace App\Game\Infrastructure\Doctrine\Repository;

use App\Game\Domain\Model\Chat;
use App\Game\Domain\Repository\ChatRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChatRepository extends ServiceEntityRepository implements ChatRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Chat::class);
    }

    /**
     * @param int $gameId
     * @return array<int, mixed>
     */
    public function getByGame(int $gameId): array
    {
        return $this->createQueryBuilder('c')
            ->select('
            c.id as id,
            u.username as username,
            c.text as text
        ')
            ->innerJoin('c.gameParticipant', 'gp')
            ->innerJoin('gp.user', 'u')
            ->where('gp.game = :gameId')
            ->setParameter('gameId', $gameId)
            ->orderBy('c.created', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function add(Chat $chat): self
    {
        $this->getEntityManager()->persist($chat);

        return $this;
    }

    public function remove(Chat $chat): void
    {
        $this->getEntityManager()->remove($chat);
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}
