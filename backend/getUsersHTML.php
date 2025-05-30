<?php 
require 'connection.php';
require 'verifyLogin.php';
require 'setUserActiveNow.php';
require '../components/MessageList.php';

if (!verifyLogin($jwt)) {
	echo "No Users found";
	exit();
}

$current_user_id = $jwt->validateToken($_COOKIE['webchat_token'])['payload']['user_id'];

$search = isset($_GET['search']) ? $_GET['search'] : "";

echo !empty($search) ? "<p class=\"w-full pl-2 pb-2\">Search Result:</p>" : "";

$sql = "
	SELECT 
	    users.user_id, 
	    users.full_name, 
	    users.picture, 
	    last_message.text_sent, 
	    last_message.sent_dt
	FROM 
	    users
	LEFT JOIN (
	    SELECT 
	        t1.chat_id,
	        t1.text_sent, 
	        t1.sent_dt,
	        CASE 
	            WHEN t1.sender_user_id = :current_user_id THEN t1.receiver_user_id
	            ELSE t1.sender_user_id
	        END AS user_id
	    FROM 
	        chats t1
	    LEFT JOIN chats t2 ON
	        ((t1.sender_user_id = t2.sender_user_id AND t1.receiver_user_id = t2.receiver_user_id) OR
	         (t1.sender_user_id = t2.receiver_user_id AND t1.receiver_user_id = t2.sender_user_id))
	        AND t1.sent_dt < t2.sent_dt
	    WHERE 
	        t2.chat_id IS NULL
	        AND (t1.sender_user_id = :current_user_id OR t1.receiver_user_id = :current_user_id)
	) AS last_message 
	ON 
	    users.user_id = last_message.user_id
	WHERE
		users.user_id != :current_user_id
";

if ($search !== "") {
    $sql .= " AND users.full_name LIKE :search";
}

$sql .= " ORDER BY last_message.sent_dt DESC;";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':current_user_id', $current_user_id);

if ($search !== "") {
    $searchTerm = '%' . $search . '%';
    $stmt->bindParam(':search', $searchTerm);
}

$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) > 0) {
	foreach ($rows as $row) {
		$lastTextSent = $row['text_sent'] ? $row['text_sent'] : "(No conversation)";
		cardMessageList($row['full_name'], $row['user_id'], $lastTextSent);
	}
} else {
	echo "<p class=\"text-center\"><br>No users found</p>";
}

setUserActiveNow($pdo, $current_user_id);

?>