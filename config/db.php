<?php 
function PDO(){
    $db_host    = "172.17.0.4";
    $db_user    = "root";
    $db_pass    = "root";
    $db_select  = "project0707";
    $dbconnect  = "mysql:host=".$db_host.";dbname=".$db_select;
    $dbgo = new PDO($dbconnect, $db_user, $db_pass);
   
    return $dbgo;
}
    
?>