<?php
require 'JWTHandler.php';

$jwt = new JWTHandler();

function verifyLogin($jwt) {
	if (empty($_COOKIE['webchat_token'])) {
		return false;
	}
	$jwt_valid = $jwt->validateToken($_COOKIE['webchat_token']);
	return $jwt_valid['is_valid'];
}
?>