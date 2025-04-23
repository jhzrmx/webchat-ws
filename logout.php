<?php 
setcookie('webchat_token', '', time() + (86400 * 30), '/');
header('location: components/');
?>