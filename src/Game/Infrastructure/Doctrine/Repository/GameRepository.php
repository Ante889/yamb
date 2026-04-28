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
