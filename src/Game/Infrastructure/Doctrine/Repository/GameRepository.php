<?php

namespace App\Game\Infrastructure\Doctrine\Repository;

use App\Game\Domain\Model\Game;
use App\Game\Domain\Repository\GameRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GameRepository extends ServiceEntityRepository implements GameRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function existsByJoinCode(string $joinCode): bool
    {
        return (bool) $this->createQueryBuilder('g')
            ->select('1')
            ->andWhere('g.joinCode = :code')
            ->setParameter('code', $joinCode)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function add(Game $game): self
    {
        $this->getEntityManager()->persist($game);

        return $this;
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}
