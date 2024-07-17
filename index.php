<?php
require 'backend/connection.php';
require 'backend/verifyLogin.php';
require 'backend/generateUID.php';
require 'components/HTML.php';
require 'components/Login.php';
require 'components/Signup.php';
require 'components/SweetAlert.php';

$html = new HTML("WebChat");
$html->addLink('icon', 'https://static.xx.fbcdn.net/rsrc.php/yb/r/hLRJ1GG_y0J.ico');
$html->addScript("js/tailwind3.4.5.js");
$html->addScript("js/sweetalert.min.js");
$html->startBody();

if (isset($_GET['page'])) {
	if ($_GET['page'] === 'signup') {
		signupPage("WebChat - Signup");
	} else {
		header('location: components/');
	}
} else {
	loginPage("WebChat - Login");
}

$cookieOptions = [
    'expires' => time() + (86400 * 30), // 1 month
    'path' => '/',
    'secure' => true, // Ensure this is set to true if SameSite=None
    'samesite' => 'None' // Set SameSite attribute
];

if (verifyLogin($pdo)) {
	header('location: mychat/');
} else {
	swal("Please login again.", "", "info");
}

try {
	if (isset($_POST['login'])) {
		$stmt = $pdo->prepare("SELECT * FROM `accounts` JOIN `users` ON `accounts`.`user_id` = `users`.`user_id` WHERE `accounts`.`username` = :username");
		$stmt->bindParam(':username', $_POST['username']);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($rows) > 0) {
			foreach ($rows as $row) {
				if (password_verify($_POST['password'], $row['password'])) {
					setcookie('wcipa-ai', $row['account_id'], time() + (86400 * 30), '/');
					setcookie('wcipa-ui', $row['user_id'], time() + (86400 * 30), '/');
					setcookie('wcipa-pw', $row['password'], time() + (86400 * 30), '/');
					swalThen("Login Successful", "Welcome, " . $row['full_name'], "success", "() => {window.location.href = 'mychat/'}");
				} else {
					swal("Wrong Password", "", "error");
				}
				break;
			}
		} else {
			swal("Error", "User not found", "error");
		}
	} elseif (isset($_POST['signup'])) {
		$stmt = $pdo->prepare("SELECT * FROM `accounts` WHERE `username` = :username");
		$stmt->bindParam(':username', $_POST['username']);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($rows) == 0) {
			$randomUserId = generateUID();
			$stmt_usr = $pdo->prepare("INSERT INTO `users` (`user_id`, `full_name`, `last_active`) VALUES (:user_id, :full_name, NOW())");
			$stmt_usr->bindParam(':user_id', $randomUserId);
			$stmt_usr->bindParam(':full_name', $_POST['fullname']);

			$randomAccountId = generateUID();
			$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$stmt_acc = $pdo->prepare("INSERT INTO `accounts` (`account_id`, `username`, `password`, `user_id`) VALUES (:account_id, :username, :password, :user_id)");
			$stmt_acc->bindParam(':account_id', $randomAccountId);
			$stmt_acc->bindParam(':username', $_POST['username']);
			$stmt_acc->bindParam(':password', $hashedPassword);
			$stmt_acc->bindParam(':user_id', $randomUserId);

			$stmt_usr->execute();
			$stmt_acc->execute();
			setcookie('wcipa-ui', $randomUserId, time() + (86400 * 30), '/');
			setcookie('wcipa-ai', $randomAccountId, time() + (86400 * 30), '/');
			setcookie('wcipa-pw', $hashedPassword, time() + (86400 * 30), '/');
			swalThen("Signup Successful", "Welcome, " . $_POST['fullname'], "success", "() => {window.location.href = 'mychat/'}");
		} else {
			swal("Oops", "Username already taken", "error");
		}
	}
} catch (PDOException $e) {
	swal("Error", $e->getMessage(), "error");
}
$html->endBody();
?>