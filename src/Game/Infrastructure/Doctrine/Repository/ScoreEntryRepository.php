<?php

namespace App\Game\Infrastructure\Doctrine\Repository;

use App\Game\Domain\Enum\ScoreEntryColumnEnum;
use App\Game\Domain\Enum\ScoreEntryFieldEnum;
use App\Game\Domain\Model\GameParticipant;
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

    public function updateForLeaver(int $gameParticipant): int
    {
        return $this->createQueryBuilder('se')
            ->update()
            ->set('se.value', ':zero')
            ->andWhere('se.gameParticipant = :gameParticipantId')
            ->setParameter('zero', 0)
            ->setParameter('gameParticipantId', $gameParticipant)
            ->getQuery()
            ->execute();
    }

    public function getByGame(int $gameId): array
    {
        return $this->createQueryBuilder('se')
            ->innerJoin('se.gameParticipant', 'gp')
            ->innerJoin('gp.game', 'g')
            ->andWhere('g.id = :gameId')
            ->setParameter('gameId', $gameId)
            ->getQuery()
            ->getResult();
    }

    public function checkUpDownRule(ScoreEntryFieldEnum $row, ScoreEntryColumnEnum $column, int $participantId): bool
    {
        $result = $this->createQueryBuilder('se')
            ->select('1')
            ->where('se.gameParticipant = :gameParticipantId')
            ->andWhere('se.entryColumn = :down')
            ->andWhere('se.field = :row')
            ->setParameter('gameParticipantId', $participantId)
            ->setParameter('row', $row->value)
            ->setParameter('down', $column->value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $result !== null;
    }

    public function existsForParticipantAndPosition(
        int $participantId,
        ScoreEntryColumnEnum $column,
        ScoreEntryFieldEnum $row
    ): bool
    {
        return (bool) $this->createQueryBuilder('se')
            ->select('COUNT(se.id)')
            ->andWhere('se.gameParticipant = :participantId')
            ->andWhere('se.entryColumn = :column')
            ->andWhere('se.field = :row')
            ->setParameter('participantId', $participantId)
            ->setParameter('column', $column)
            ->setParameter('row', $row)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function deleteByGameId(int $gameId): int
    {
        return $this->getEntityManager()
            ->createQuery(
                'DELETE FROM App\Game\Domain\Model\ScoreEntry se
                 WHERE se.gameParticipant IN (
                 SELECT gp
                 FROM App\Game\Domain\Model\GameParticipant gp
                 WHERE gp.game = :gameId
             )'
            )
            ->setParameter('gameId', $gameId)
            ->execute();
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
