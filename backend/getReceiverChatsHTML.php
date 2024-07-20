<?php 
require 'connection.php';
require 'verifyLogin.php';
require '../components/ChatBubbles.php';

date_default_timezone_set('Asia/Manila');

if (!verifyLogin($pdo) || !isset($_GET['uid'])) {
	echo "<p class=\"w-full flex items-center justify-center\">Unable to fetch messages</p>";
	exit();
}

$senderUserId = $_COOKIE['wcipa-ui'];
$receiverUserId = $_GET['uid'];

$stmt = $pdo->prepare("
    SELECT * FROM chats 
    WHERE (sender_user_id = :sender_user_id AND receiver_user_id = :receiver_user_id) 
       OR (sender_user_id = :receiver_user_id AND receiver_user_id = :sender_user_id) 
    ORDER BY sent_dt DESC
    LIMIT 30
");
$stmt->execute([
    ':sender_user_id' => $senderUserId,
    ':receiver_user_id' => $receiverUserId
]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

function timeSince($date) {
    $intervals = [
        ["label" => "year", "seconds" => 31536000],
        ["label" => "month", "seconds" => 2592000],
        ["label" => "day", "seconds" => 86400],
        ["label" => "hour", "seconds" => 3600],
        ["label" => "minute", "seconds" => 60],
    ];
    $now = new DateTime();
    $seconds = $now->getTimestamp() - $date->getTimestamp();
    foreach ($intervals as $interval) {
        $count = floor($seconds / $interval["seconds"]);
        if ($count >= 1) {
            return $count . ' ' . $interval["label"] . ($count > 1 ? 's' : '') . ' ago';
        }
    }
    return "just now";
}

$messages = array_reverse($messages);

if (count($messages) > 0) {
    foreach ($messages as $message) {
        $sentDate = new DateTime($message['sent_dt']);
        if ($message['sender_user_id'] === $_COOKIE['wcipa-ui']) {
            senderBubble($message['text_sent'], timeSince($sentDate));
        } else {
            receiverBubble($message['text_sent'], timeSince($sentDate));
        }
    }
} else {
    echo "<p class=\"w-full flex items-center justify-center\">No conversations</p>";
}

?>