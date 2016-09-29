<?php
  session_start();
  // If the session vars aren't set, try to set them with a cookie
  if (!isset($_SESSION['bossid'])) {
    if (isset($_COOKIE['bossid']) && isset($_COOKIE['bossname'])) {
      $_SESSION['bossid'] = $_COOKIE['bossid'];
      $_SESSION['bossname'] = $_COOKIE['bossname'];
    }
  }
  if (!isset($_SESSION['bossid'])) {
    $login_url=$base_url."login.php";
    header('Location:  ' . $login_url);
	exit;
  }

?>
