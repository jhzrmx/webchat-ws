<?php
function setUserActiveNow($pdo, $current_user_id) {
	$stmt_active = $pdo->prepare("UPDATE `users` SET `last_active` = NOW() WHERE `user_id` = :current_user_id");
	$stmt_active->bindParam(':current_user_id', $current_user_id);
	$stmt_active->execute();
}
?>