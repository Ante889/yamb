<?php

namespace App\Game\Infrastructure\Doctrine\Repository;

use App\Game\Domain\Model\GameParticipant;
use App\Game\Domain\Repository\GameParticipantRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GameParticipantRepository extends ServiceEntityRepository implements GameParticipantRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameParticipant::class);
    }

    public function getDiceNumberById(int $id): ?int
    {
        $result = $this->createQueryBuilder('gp')
            ->select('g.diceNumber')
            ->innerJoin('gp.game', 'g')
            ->where('gp.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (int) $result : null;
    }

    public function findById(int $id): ?GameParticipant
    {
        return $this->createQueryBuilder('gp')
            ->andWhere('gp.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deleteByGameId(int $gameId): int
    {
        return $this->createQueryBuilder('gp')
            ->delete()
            ->where('gp.game = :gameId')
            ->setParameter('gameId', $gameId)
            ->getQuery()
            ->execute();
    }

    public function add(GameParticipant $gameParticipant): self
    {
        $this->getEntityManager()->persist($gameParticipant);

        return $this;
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}
