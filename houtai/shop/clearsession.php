<?php 
session_start();
if (isset($_SESSION['shopid'])) {
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time() - 3600);
	}
	session_destroy();
}
setcookie('shopid', '', time() - 3600);
setcookie('shopname', '', time() - 3600);
setcookie('role', '', time() - 3600);
setcookie('servername', '', time() - 3600);
?>