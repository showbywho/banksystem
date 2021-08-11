<?php

namespace App\Repository;

use App\Entity\Refund;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RefundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Refund::class);
    }

    /**
     * 列表查詢
     *
     * @param string|int $id 當前使用者ID
     *
     * @return array
     */
    public function getList($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $result = $qb->select('r')->from('App\Entity\Refund', 'r')
            ->where('r.userId=:userId')
            ->setParameter('userId', $id)
            ->getQuery()
            ->getArrayResult();

        return $result;
    }
}
