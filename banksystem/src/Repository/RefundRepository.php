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
        $result = $qb->select('r')->from('App\Entity\Refund', 'r')->where("r.userId='$id'")->getQuery()->getArrayResult();

        return $result;
    }

    /**
     *
     * @param string|int $amount 數量
     * @param string $userId 當前使用者ID
     * @param object $requestSession session物件
     *
     * @return array
     */
    public function doRefund($amount, $userId, $requestSession)
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
        $totalRefund = $userData['totalRefund'];
        $nickName = $userData['nickName'];

        if ($amount > $balance) {
            return ['error' => $balance];
        }

        $addAmount = $balance - $amount;
        $addTotalRefund = $totalRefund + $amount;
        $em = $this->getEntityManager();
        $refundInsert = new Refund();
        $refundInsert->setTradeNo('tradeNO' . strtotime("now") . rand(100, 999))
            ->setUserId($userId)
            ->setUserName($nickName)
            ->setAmount(intval($amount))
            ->setBeforeBalance($balance)
            ->setAfterBalance($addAmount)
            ->setCreateTime(new \DateTime())
            ->setStatus('1');
        $em->persist($refundInsert);
        $em->flush();

        $em->createQueryBuilder()->update('App\Entity\Admin', 'a')
            ->set('a.balance', $addAmount)
            ->set('a.totalRefund', $addTotalRefund)
            ->where("a.id = $userId")->getQuery()->execute();;
        $requestSession->getSession()->set('balance', $addAmount);

        return ['success' => $addAmount];
    }
}
