<?php
function verifyLogin($pdo) {
	try {
		if (isset($_COOKIE['wcipa-ai']) && isset($_COOKIE['wcipa-ui']) && isset($_COOKIE['wcipa-pw'])) {
			$stmt = $pdo->prepare("SELECT * FROM `accounts` JOIN `users` ON `accounts`.`user_id` = `users`.`user_id`  WHERE `accounts`.`account_id` = :account_id");
			$stmt->bindParam(':account_id', $_COOKIE['wcipa-ai']);
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (count($rows) > 0) {
				foreach ($rows as $row) {
					if ($_COOKIE['wcipa-pw'] === $row['password']) {
						return true;
					} else {
						return false;
					}
					break;
				}
			} else {
				return false;
			}
		}
	} catch (PDOException $e) {
		return false;
	}
}
?>