<?php 
require_once ('/var/www/html/houtai/shop/global.php');
session_start();
// If the session vars aren't set, try to set them with a cookie
if (!isset($_SESSION['shopid'])) {
	if (isset($_COOKIE['shopid']) && isset($_COOKIE['shopname'])) {
		$_SESSION['shopid'] = $_COOKIE['shopid'];
		$_SESSION['shopname'] = $_COOKIE['shopname'];
		$_SESSION['logo'] = $_COOKIE['logo'];
		$_SESSION['roleid'] = $_COOKIE['roleid'];
		$_SESSION['serverid'] = $_COOKIE['serverid'];
		$_SESSION['role'] = $_COOKIE['role'];
		$_SESSION['servername'] = $_COOKIE['servername'];
	}
}
if (!isset($_SESSION['shopid'])) {
	$login_url=$base_url."login.php";
	header('Location:  ' . $login_url);
	exit;
}

?>