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
        $result = $qb->select('r')->from('App\Entity\Incoming', 'r')
            ->where("r.userId='$id'")
            ->getQuery()
            ->getArrayResult();

        return $result;
    }

    /**
     *
     * @param string|int $amount 數量
     * @param string $userId 當前使用者ID
     * @param object $requestSession session物件
     *
     * @return int
     */
    public function doIncoming($amount, $userId, $requestSession)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $result = $qb->select('a')->from('App\Entity\Admin', 'a')
            ->where("a.id='$userId'")
            ->getQuery()
            ->getArrayResult();
        $userData = [];

        foreach ($result[0] as $k => $v) {
            $userData[$k] = $v;
        }

        $balance = $userData['balance'];
        $totalDeposit = $userData['totalDeposit'];
        $nickName = $userData['nickName'];
        $countAmount = $balance  + $amount;
        $addTotalDeposit = $totalDeposit + $amount;
        $em = $this->getEntityManager();
        $incomingInsert = new Incoming();
        $incomingInsert->setTradeNo('tradeNO' . strtotime("now") . rand(100, 999))
            ->setUserId($userId)
            ->setUserName($nickName)
            ->setAmount(intval($amount))
            ->setBeforeBalance($balance)
            ->setAfterBalance($countAmount)
            ->setCreateTime(new \DateTime())
            ->setStatus('1');
        $em->persist($incomingInsert);
        $em->flush();
        $em->createQueryBuilder()->update('App\Entity\Admin', 'a')
            ->set('a.balance', $countAmount)
            ->set('a.totalDeposit', $addTotalDeposit)
            ->where("a.id = $userId")
            ->getQuery()
            ->execute();;
        $requestSession->getSession()->set('balance', $countAmount);

        return $countAmount;
    }
}
