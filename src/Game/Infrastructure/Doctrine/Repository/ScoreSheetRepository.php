<?php

namespace App\Game\Infrastructure\Doctrine\Repository;

use App\Game\Domain\Model\ScoreSheet;
use App\Game\Domain\Repository\ScoreSheetRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ScoreSheetRepository extends ServiceEntityRepository implements ScoreSheetRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScoreSheet::class);
    }

    public function add(ScoreSheet $scoreSheet): self
    {
        $this->getEntityManager()->persist($scoreSheet);

        return $this;
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }
}
