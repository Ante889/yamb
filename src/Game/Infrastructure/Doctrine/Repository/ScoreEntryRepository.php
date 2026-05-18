<?php

namespace App\Game\Infrastructure\Doctrine\Repository;

use App\Game\Domain\Model\ScoreEntry;
use App\Game\Domain\Repository\ScoreEntryRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ScoreEntryRepository extends ServiceEntityRepository implements ScoreEntryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScoreEntry::class);
    }

    public function getByGameParticipantId(int $gameParticipant): array
    {
        return $this->createQueryBuilder('se')
            ->andWhere('se.gameParticipant = :gameParticipantId')
            ->setParameter('gameParticipantId', $gameParticipant)
            ->getQuery()
            ->getResult();
    }

    public function add(ScoreEntry $scoreEntry): self
    {
        $this->getEntityManager()->persist($scoreEntry);

        return $this;
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}
