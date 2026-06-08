<?php

namespace App\User\Infrastructure\Doctrine\Repository;

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
