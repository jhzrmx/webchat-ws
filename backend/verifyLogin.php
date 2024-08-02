<?php
function verifyLogin($pdo) {
	try {
		if (isset($_COOKIE['wcipa-ai']) && isset($_COOKIE['wcipa-ui']) && isset($_COOKIE['wcipa-pw'])) {
			$stmt = $pdo->prepare("SELECT * FROM `accounts` JOIN `users` ON `accounts`.`user_id` = `users`.`user_id`  WHERE `accounts`.`account_id` = :account_id AND `accounts`.`password` = :password");
			$stmt->bindParam(':account_id', $_COOKIE['wcipa-ai']);
			$stmt->bindParam(':password', $_COOKIE['wcipa-pw']);
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return count($rows) > 0;
		}
	} catch (PDOException $e) {
		return false;
	}
}
?>