<?php

namespace App\Repository;

use App\Entity\Admin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;

class AdminRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admin::class);
    }

    /**
     * 使用者列表查詢
     *
     * @param string|int $id 當前使用者ID
     *
     * @return array
     */
    public function getList($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $result = $qb->select('a')->from('App\Entity\Admin', 'a')
            ->where('a.id=:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();

        return $result;
    }

    /**
     * 登入驗證
     *
     * @param string $account 帳號
     * @param string $password 密碼
     * @param object $sessionObj session物件
     *
     * @return array
     */
    public function loginValidation($account, $password, $sessionObj)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $result = $qb->select('a')->from('App\Entity\Admin', 'a')
            ->where('a.account = :account AND a.password = :password')
            ->setParameter('account', $account)
            ->setParameter('password', $password)
            ->getQuery()
            ->getArrayResult();
        $data = [];

        if ($result) {
            $sessionObj->set('id', $result[0]['id']);
            $sessionObj->set('nickName', $result[0]['nickName']);
            $sessionObj->set('balance', $result[0]['balance']);
            $result = ['id' => $result[0]['id'], 'nickName' => $result[0]['nickName']];
            $data = $result;
        }

        return $data;
    }

    /**
     * 使用樂觀鎖計算存款餘額
     *
     * @param string|int $amount 數量
     * @param string $userId 當前使用者ID
     *
     * @return array
     */
    public function updateBalanceOptimistic($amount, $userId)
    {
        $em = $this->getEntityManager();
        $admin = $em->find('App\Entity\Admin', $userId);
        $balance = $admin->getBalance();
        $totalDeposit = $admin->getTotalDeposit();
        $nickName = $admin->getNickName();
        $version = $admin->getVersion();
        $countAmount = $balance + $amount;
        $addTotalDeposit = $totalDeposit + $amount;
        $em->beginTransaction();
        $status = '1';

        try {
            $em->lock($admin, LockMode::PESSIMISTIC_READ, $version);
            $admin->setBalance($countAmount);
            $admin->setTotalDeposit($addTotalDeposit);
            $em->persist($admin);
            $em->flush();
            $em->commit();
        } catch (OptimisticLockException $e) {
            $status = '2';
            $countAmount = $balance;
            $em->rollBack();
        }

        return [
            'status' => $status,
            'countAmount' => $countAmount,
            'nickName' => $nickName,
            'balance' => $balance
        ];
    }

    /**
     * 使用悲觀鎖計算提現餘額
     *
     * @param string|int $amount 數量
     * @param string $userId 當前使用者ID
     *
     * @return array
     */
    public function updateBalancePessimistic($amount, $userId)
    {
        $em = $this->getEntityManager();
        $admin = $em->find('App\Entity\Admin', $userId);
        $balance = $admin->getBalance();
        $totalRefund = $admin->getTotalRefund();
        $nickName = $admin->getNickName();
        $countAmount = $balance - $amount;
        $addTotalRefund = $totalRefund + $amount;
        $status = '1';

        if ($amount > $balance) {
            return ['error' => $balance];
        }

        $em->beginTransaction();

        try {
            $em->lock($admin, LockMode::PESSIMISTIC_READ);
            $admin->setBalance($countAmount);
            $admin->setTotalRefund($addTotalRefund);
            $em->persist($admin);
            $em->flush();
            $em->commit();
        } catch (OptimisticLockException $e) {
            $status = '2';
            $countAmount = $balance;
            $em->rollBack();
        }

        return [
            'status' => $status,
            'countAmount' => $countAmount,
            'nickName' => $nickName,
            'balance' => $balance
        ];
    }
}
