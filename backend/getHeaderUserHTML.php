<?php 
require 'connection.php';
require 'verifyLogin.php';
require '../components/HeaderUser.php';

if (!verifyLogin($jwt) || !isset($_GET['uid'])) {
	echo "User not found";
	exit();
}

$stmt = $pdo->prepare(" SELECT `full_name`, `picture` FROM users WHERE `user_id` = :retrieved_user_id; ");
$stmt->bindParam(':retrieved_user_id', $_GET['uid']);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) > 0) {
	foreach ($rows as $row) {
		headerUser($row['full_name']);
	}
} else {
	headerUser("User not found");
}

?>