<?php 
require 'connection.php';
require 'verifyLogin.php';
require '../components/HeaderUser.php';

header('Content-Type: application/json');

if (!verifyLogin($pdo) || !isset($_GET['uid'])) {
	echo json_encode(['success' => false]);
	exit();
}

$stmt = $pdo->prepare(" SELECT `full_name`, `picture` FROM users WHERE `user_id` = :retrieved_user_id; ");
$stmt->bindParam(':retrieved_user_id', $_GET['uid']);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
	'success' => true,
    'header' => $rows
]);

?>