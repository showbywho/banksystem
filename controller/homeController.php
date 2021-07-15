<?php
require_once 'bootstrap.php';
use Doctrine\ORM\Tools\Pagination\Paginator;

$page = isset($_GET['p']) ? $_GET['p'] : 1;
$page = ($page === 0) ? 1 : $page;
$num = 5;
$offset = ($page - 1) * $num;
$dql = 'SELECT m FROM MsgBoard m ORDER BY m.id DESC';
$query = $entityManager->createQuery($dql)
    ->setFirstResult($offset)
    ->setMaxResults($num);

$comments = new Paginator($query, $fetchJoinCollection = true);
$totalComments = count($comments);
$totalPage = ceil($totalComments / $num);
$page = ($page === $totalPage) ? ($totalPage - 1) : $page;
