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
     * 使用者存款明細列表查詢
     *
     * @param int $id 當前使用者ID
     *
     * @return array
     */
    public function getUserIncomingList($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $result = $qb->select('i')
            ->from('App\Entity\Incoming', 'i')
            ->where('i.userId = :user_id')
            ->setParameter('user_id', $id)
            ->getQuery()
            ->getArrayResult();

        return $result;
    }
}
