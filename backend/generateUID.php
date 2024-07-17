<?php
function generateUID() {
	$prefix = uniqid('', true);
	$uuid = substr(md5($prefix), 0, 36);
	if (strlen($uuid) < 36) {
	    $bytes_needed = 36 - strlen($uuid);
	    $bytes = random_bytes($bytes_needed);
	    $uuid .= substr(bin2hex($bytes), 0, $bytes_needed);
	}
	return substr($uuid, 0, 36);
}
?>