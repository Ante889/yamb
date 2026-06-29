<?php

namespace App\Game\Infrastructure\Doctrine\Repository;

use App\Game\Domain\Model\Game;
use App\Game\Domain\Repository\GameRepositoryInterface;
use App\Shared\Domain\Paginator\Paginator;
use App\Shared\Domain\Paginator\Value\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\Persistence\ManagerRegistry;

class GameRepository extends ServiceEntityRepository implements GameRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Game::class);
    }

    public function findById(int $gameId): ?Game
    {
        return  $this->createQueryBuilder('g')
            ->where('g.id = :id')
            ->setParameter('id', $gameId)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function deleteById(int $gameId): int
    {
        return $this->createQueryBuilder('g')
            ->delete()
            ->where('g.id = :id')
            ->setParameter('id', $gameId)
            ->getQuery()
            ->execute();
    }

    public function getPublic(): array
    {
        return $this->createQueryBuilder('g')
                ->select('
                    g.id as id,
                    g.diceNumber as diceNumber,
                    g.gameType as gameType,
                    GROUP_CONCAT(u.username) as gameUsers
                ')
                ->leftJoin('g.participants', 'gp')
                ->leftJoin('gp.user', 'u')
                ->where('g.isPublic = :isPublic')
                ->setParameter('isPublic', true)
                ->groupBy('g.id')
                ->orderBy('g.id', 'DESC')
                ->getQuery()
                ->getResult();
    }

    public function getFirstFiftyByType(string $type, int $dice): array
    {
        return $this->createQueryBuilder('g')
                ->select('
                u.id as id,
                u.username as username,
                MAX(p.points) as points
            ')
            ->innerJoin('g.participants', 'p')
            ->innerJoin('p.user', 'u')
            ->where('g.gameType = :type')
            ->andWhere('p.points IS NOT NULL')
            ->andWhere('g.diceNumber = :dice')
            ->setParameter('type', $type)
            ->setParameter('dice', $dice)
            ->setMaxResults(50)
            ->groupBy('u.id')
            ->orderBy('p.points', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByJoinCode(string $joinCode): ?Game
    {
        return  $this->createQueryBuilder('g')
            ->where('g.joinCode = :joinCode')
            ->setParameter('joinCode', $joinCode)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function gameByGameParticipant(int $gameParticipantId): ?Game
    {
        return  $this->createQueryBuilder('g')
            ->innerJoin('g.participants', 'p')
            ->andWhere('p.id = :participantId')
            ->setParameter('participantId', $gameParticipantId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
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
