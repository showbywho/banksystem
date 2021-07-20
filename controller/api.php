<?php
require_once '../bootstrap.php';

if ($_GET['tag'] === 'see') {
    getMsgReply($_GET['pmId'], $entityManager);
} elseif ($_GET['tag'] === 'reply') {
    sendMsgReply($_GET['pmId'], $entityManager);
} elseif ($_GET['tag'] === 'newMsg') {
    newMsgReply($entityManager);
} elseif ($_GET['tag'] === 'updateMsg') {
    updateMsgReply($_GET['id'], $entityManager);
} elseif ($_GET['tag'] === 'del') {
    delMsgReply($_POST['listId'], $entityManager);
}

function getMsgReply($pmId, $em)
{
    $query = $em->createQuery('SELECT r FROM Reply r WHERE r.tag = ' . $pmId);
    $users = $query->getArrayResult(); // 將撈出來的資料陣列化
    echo json_encode($users);
    exit;
}

function sendMsgReply($pmId, $em)
{
    $names = $_POST['names'];
    $contents = $_POST['contents'];
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $times = date('Y-m-d H:i:s');
    $sql = "INSERT INTO Reply (contents, owner, times ,user_ip ,tag) VALUES ('$contents','$names','$times','$ip','$pmId')";
    $em->getConnection()->executeUpdate($sql);
    header('Location: http://localhost:8083/calvin_yang/');

}

function newMsgReply($em)
{
    $names = $_POST['names'];
    $contents = $_POST['contents'];
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $times = date('Y-m-d H:i:s');
    $sql = "INSERT INTO MsgBoard (contents, owner, times ,user_ip ) VALUES ('$contents','$names','$times','$ip')";
    $em->getConnection()->executeUpdate($sql);
    header('Location: http://localhost:8083/calvin_yang/');
}

function updateMsgReply($id, $em)
{
    $contents = $_POST['contents'];
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $msgBoard = $em->find('MsgBoard', $id);
    $msgBoard->setContents($contents);
    $msgBoard->setUserIp($ip);
    $em->flush();
    header('Location: http://localhost:8083/calvin_yang/');
}

function delMsgReply($id, $em)
{
    $query = $em->createQuery('DELETE FROM MsgBoard m WHERE m.id = ' . $id);
    $query->execute();
    $query = $em->createQuery('DELETE FROM Reply r WHERE r.tag = ' . $id);
    $query->execute();
    header('Location: http://localhost:8083/calvin_yang/');
}
