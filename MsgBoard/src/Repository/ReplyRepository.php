<?php

namespace App\Repository;

use App\Entity\Reply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reply::class);
    }

    /**
     * 回覆留言查詢
     *
     * @param string $pmId 主留言的唯一id 對應回覆留言的tag欄位
     * @return array 查詢結果陣列
     */
    public function msgReply($pmId)
    {
        $em = $this->getEntityManager();
        $query = $em->getRepository('App\Entity\Reply')->findby(['tag' => $pmId]);
        $arrayQuery = [];

        foreach ($query as $obj) {
            $arrayQuery[] = $obj->toArray();
        }

        return $arrayQuery;
    }
}
