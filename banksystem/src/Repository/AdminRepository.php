<?php

namespace App\Repository;

use App\Entity\Admin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
        $result = $qb->select('r')->from('App\Entity\Admin', 'r')
            ->where("r.id='$id'")
            ->getQuery()
            ->getArrayResult();

        return $result;
    }

    /**
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
            ->where("a.account = '$account' AND a.password = '$password'")
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
}
