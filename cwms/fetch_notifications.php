<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['userlogin'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit;
}

$userid = $_SESSION['userlogin'];

$sql = "SELECT id, message, is_read, created_at 
        FROM tblnotifications 
        WHERE userId = :uid 
        ORDER BY created_at DESC
        LIMIT 10";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':uid', $userid, PDO::PARAM_INT);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$countSql = "SELECT COUNT(*) FROM tblnotifications WHERE userId = :uid AND is_read = 0";
$countStmt = $dbh->prepare($countSql);
$countStmt->bindParam(':uid', $userid, PDO::PARAM_INT);
$countStmt->execute();
$unread = (int)$countStmt->fetchColumn();

echo json_encode(['unread' => $unread, 'notifications' => $notifications]);
