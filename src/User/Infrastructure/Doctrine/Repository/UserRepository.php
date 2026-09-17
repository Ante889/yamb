<?php

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Game\Domain\Enum\GameStatusEnum;
use App\Game\Domain\Model\GameParticipant;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findById(int $userId): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.active = :active')
            ->andWhere('u.id = :id')
            ->setParameter('id', $userId)
            ->setParameter('active', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getDuelStatisticByUser(User $user): array
    {
        $subQuery = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('MAX(gp2.points)')
            ->from(GameParticipant::class, 'gp2')
            ->where('gp2.game = game');

        return $this->createQueryBuilder('u')
            ->select([
                'COUNT(game.id) AS totalGames',
                'SUM(
                CASE
                    WHEN gameParticipant.points = (' . $subQuery->getDQL() . ')
                    THEN 1
                    ELSE 0
                END
            ) AS totalWins',
            ])
            ->innerJoin('u.participants', 'gameParticipant')
            ->innerJoin('gameParticipant.game', 'game')
            ->andWhere('u.id = :userId')
            ->andWhere('game.maxPlayers > 1')
            ->andWhere('game.gameStatus = :finished')
            ->setParameter('userId', $user->getId())
            ->setParameter('finished', GameStatusEnum::FINISHED->value)
            ->getQuery()
            ->getSingleResult();
    }

    public function getRecordsByUser(User $user): array
    {
        return $this->createQueryBuilder('u')
            ->select([
                'game.gameType AS gameType',
                'game.diceNumber AS diceNumber',
                'MAX(gameParticipant.points) AS points',
            ])
            ->innerJoin('u.participants', 'gameParticipant')
            ->innerJoin('gameParticipant.game', 'game')
            ->andWhere('u.id = :userId')
            ->andWhere('game.gameStatus = :finished')
            ->setParameter('userId', $user->getId())
            ->setParameter('finished', GameStatusEnum::FINISHED->value)
            ->groupBy('game.gameType')
            ->addGroupBy('game.diceNumber')
            ->getQuery()
            ->getArrayResult();
    }

    public function findByGameParticipantId(int $participantId): ?User
    {
        static $cache = [];

        if (isset($cache[$participantId])) {
            return $cache[$participantId];
        }

        return $cache[$participantId] = $this->createQueryBuilder('u')
            ->innerJoin('u.participants', 'p')
            ->where('p.id = :participantId')
            ->setParameter('participantId', $participantId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function existsByEmailOrUsername(string $email, string $username): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.active = :active')
            ->andWhere('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $email)
            ->setParameter('active', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function add(User $user): self
    {
        $this->getEntityManager()->persist($user);

        return $this;
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}
