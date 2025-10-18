<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['userlogin'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

$uid = $_SESSION['userlogin'];
$sql = "UPDATE tblnotifications SET is_read = 1 WHERE userId = :uid AND is_read = 0";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();

echo json_encode(['status'=>'ok']);
