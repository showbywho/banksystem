<?php
require "../config/db.php";

if ($_GET['tag'] == "see") {
    msgreplyGET($_GET['pmid']);
} else if ($_GET['tag'] == "reply") {
    msgreplySEND($_GET['pmid']);
} else if ($_GET['tag'] == "newre") {
    msgreplyNEW();
} else if ($_GET['tag'] == "updatere") {
    msgreplyUPDATE($_GET['id']);
} else if ($_GET['tag'] == "del") {
    msgreplyDEL($_POST['listid']);
}
function msgreplyGET($pmid)
{
    $PDO = PDO();
    $sql = "SELECT * FROM MESboard_reply WHERE tag=" . $pmid;
    $result = $PDO->query($sql);
    $row = $result->fetchAll(PDO::FETCH_ASSOC);
    $comments = $row;
    echo json_encode($row);
}

function msgreplySEND($pmid)
{
    $names = $_POST['names'];
    $contents = $_POST['contents'];
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    $PDO = PDO();
    $input = array(':contents' => $contents, ':names' => $names, ':times' => date("Y-m-d H:i:s"), ':ip' => $ip, ':pmid' => $pmid);
    $sql = "INSERT INTO MESboard_reply (contents, onwer, times ,userIP ,tag) VALUES (:contents,:names,:times,:ip,:pmid)";
    $result = $PDO->prepare($sql);
    $tag = $result->execute($input);
    // print_r("./home.php");
    if ($tag) {
        header("Location: http://localhost:8083/calvin_yang/");
    } else {

    }

}

function msgreplyNEW()
{
    $names = $_POST['names'];
    $contents = $_POST['contents'];
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }

    $PDO = PDO();
    $input = array(':contents' => $contents, ':names' => $names, ':times' => date("Y-m-d H:i:s"), ':ip' => $ip);
    $sql = "INSERT INTO MESboard (contents, onwer, times ,userIP) VALUES (:contents,:names,:times,:ip)";
    $result = $PDO->prepare($sql);
    $tag = $result->execute($input);
    if ($tag) {
        header("Location: http://localhost:8083/calvin_yang/");
    } 

}

function msgreplyUPDATE($id)
{
    $contents = $_POST['contents'];
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    $PDO = PDO();
    $input = array(':contents' => $contents, ':times' => date("Y-m-d H:i:s"), ':ip' => $ip, ':id' => $id);
    $sql = "UPDATE MESboard SET contents = :contents , times = :times, userIP = :ip WHERE id = :id ";
    $result = $PDO->prepare($sql);
    $tag = $result->execute($input);
    if ($tag) {
        header("Location: http://localhost:8083/calvin_yang/");
    } else {
    }

}

function msgreplyDEL($id)
{
    $PDO = PDO();
    $input = array(':id' => $id);
    $sql = "DELETE FROM MESboard WHERE id = :id ";
    $result = $PDO->prepare($sql);
    $tag = $result->execute($input);
    if ($tag) {
        $input = array(':tag' => $id);
        $sql = "DELETE FROM MESboard_reply WHERE tag = :tag ";
        $result = $PDO->prepare($sql);
        $tag = $result->execute($input);

        header("Location: http://localhost:8083/calvin_yang/");
    } else {
    }

}
