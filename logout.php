<?php 
setcookie('wcipa-ai', '', time() + (86400 * 30), '/');
setcookie('wcipa-ui', '', time() + (86400 * 30), '/');
setcookie('wcipa-pw', '', time() + (86400 * 30), '/');

header('location: components/');
?>