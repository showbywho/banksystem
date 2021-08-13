<?php

namespace App\Repository;

use App\Entity\Incoming;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IncomingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Incoming::class);
    }

    /**
     * 列表查詢
     *
     * @param int $id 當前使用者ID
     *
     * @return array
     */
    public function getList($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $result = $qb->select('i')->from('App\Entity\Incoming', 'i')
            ->where('i.userId=:userId')
            ->setParameter('userId', $id)
            ->getQuery()
            ->getArrayResult();

        return $result;
    }
}
