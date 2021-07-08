<?php
require("../config/db.php");
msgreplyGET($_GET['pmid']);
function msgreplyGET($pmid){
    $PDO=PDO();
    $sql = "SELECT * FROM MESboard_reply WHERE tag=".$pmid;
    $result = $PDO->query($sql);
    $row=$result->fetchAll(PDO::FETCH_ASSOC);
    $comments       = $row;
    echo json_encode($row);
}


?>