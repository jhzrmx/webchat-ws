<?php 
require 'connection.php';
require 'verifyLogin.php';
require 'setUserActiveNow.php';

header('Content-Type: application/json');

if (!verifyLogin($jwt) || !isset($_GET['uid'])) {
	echo json_encode(['success' => false]);
	exit();
}

$current_user_id = $jwt->validateToken($_COOKIE['webchat_token'])['payload']['user_id'];
$receiverUserId = $_GET['uid'];

$stmt = $pdo->prepare("
    SELECT * FROM chats 
    WHERE (sender_user_id = :sender_user_id AND receiver_user_id = :receiver_user_id) 
       OR (sender_user_id = :receiver_user_id AND receiver_user_id = :sender_user_id) 
    ORDER BY sent_dt DESC 
    LIMIT 50
");
$stmt->execute([
    ':sender_user_id' => $current_user_id,
    ':receiver_user_id' => $receiverUserId
]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode([
	'success' => true,
    'uid' => $_GET['uid'],
    'conversation' => array_reverse($messages)
]);

setUserActiveNow($pdo, $current_user_id);

?>