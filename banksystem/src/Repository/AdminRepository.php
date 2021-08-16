<?php

namespace App\Repository;

use App\Entity\Admin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

class AdminRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admin::class);
    }

    /**
     * 使用者資料查詢
     *
     * @param string|int $id 當前使用者ID
     *
     * @return array
     */
    public function getUser($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $result = $qb
            ->select('a')
            ->from('App\Entity\Admin', 'a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult(Query::HYDRATE_ARRAY);

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
        $result = $qb->select('a')
            ->from('App\Entity\Admin', 'a')
            ->where('a.account = :account AND a.password = :password')
            ->setParameter('account', $account)
            ->setParameter('password', $password)
            ->getQuery()
            ->getSingleResult(Query::HYDRATE_ARRAY);

        $data = [];

        if ($result) {
            $sessionObj->set('id', $result['id']);
            $sessionObj->set('nick_name', $result['nickName']);
            $sessionObj->set('balance', $result['balance']);
            $result = [
                'id' => $result['id'],
                'nick_name' => $result['nickName'],
            ];

            $data = $result;
        }

        return $data;
    }
}
