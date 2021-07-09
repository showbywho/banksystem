<?php
$page = isset($_GET['p']) ? $_GET['p'] : 1;
$page = ($page == 0) ? 1 : $page;
$num = 5;
$offset = ($page - 1) * $num;

$PDO = PDO();
$sql = "SELECT * FROM MESboard LIMIT :page, :list_rows";
$result = $PDO->prepare($sql);
$result->bindValue(':page', (int) $offset, PDO::PARAM_INT);
$result->bindValue(':list_rows', (int) $num, PDO::PARAM_INT);
$result->execute();
$row = $result->fetchAll(PDO::FETCH_ASSOC);
$comments = $row;
$totalComments = count($row);

$all = $PDO->prepare("SELECT * FROM MESboard");
$all->execute();
$rowcount = $all->rowCount();
$totalPage = ceil($rowcount / $num);
$page = ($page == $totalPage) ? ($totalPage - 1) : $page;
