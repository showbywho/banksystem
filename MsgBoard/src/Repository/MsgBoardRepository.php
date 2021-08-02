<?php

namespace App\Repository;

use App\Entity\MsgBoard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MsgBoardRepository extends ServiceEntityRepository
{

    private $limit = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MsgBoard::class);
    }

    /**
     * 分頁頁數及總量獲取
     *
     * @param int $page 當前分頁頁面編號
     *
     * @return array
     */
    public function pageCount($page)
    {
        $page = !empty($page) ? $page : 1;
        $totalComments = $this->getEntityManager()->getRepository('App\Entity\MsgBoard')->count([]);
        $totalPage = ceil($totalComments / $this->limit);

        return ['totalComments' => $totalComments, 'totalPage' => $totalPage, 'page' => $page];
    }

    /**
     * 分頁頁面資料獲取
     *
     * @param int $page 當前分頁頁面編號
     *
     * @return array
     */
    public function pageQuery($page)
    {
        $page = !empty($page) ? $page : 1;
        $offset = ($page - 1) * $this->limit;
        $comments = $this->getEntityManager()->getRepository('App\Entity\MsgBoard')->findBy([], ['id' => 'DESC'], $this->limit, $offset);

        return $comments;
    }

}
