<?php
    $PDO=PDO();
    $sql = "SELECT * FROM MESboard";
    $result = $PDO->query($sql);
    $row=$result->fetchAll(PDO::FETCH_ASSOC);
    $comments       = $row;
    $totalComments  = count($row);
    
?>