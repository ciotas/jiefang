<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
require_once ('../clearsession.php');
class DoLoginForm{
	public function DoLogin($mobilphone,$serverphone, $password){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->DoLogin($mobilphone, $serverphone,$password);
	}
	public function doMobilphoneLogin($mobilphone,$passwd){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->doMobilphoneLogin($mobilphone, $passwd);
	}
}
$dologinform=new DoLoginForm();

if(isset($_POST['telphone'])&&isset($_POST['password'])){
	$telphone=$_POST['telphone'];
	$serverphone=$_POST['serverphone'];
	$password=$_POST['password'];
	$phonecrypt= new CookieCrypt($newphonekey);
	$mobilphone=$phonecrypt->encrypt($telphone);

	$phonecrypt= new CookieCrypt($cusphonekey);
	$serverphone=$phonecrypt->encrypt($serverphone);
	
	$pwdcrypt= new CookieCrypt($cuspwdkey);
	$password=$pwdcrypt->encrypt($password);
	if(empty($telphone)){
		header("location: ../login.php?loginerr=emptyphone");
	}elseif (empty($password)){
		header("location: ../login.php?loginerr=emptypwd");
	}else{
		$result=$dologinform->DoLogin($mobilphone, $serverphone,$password);
		switch ($result['status']){
			case "error":header("location: ../login.php?loginerr=error"); break;
			case "none":header("location: ../login.php?loginerr=none");break;
			case "none_my_server":header("location: ../login.php?loginerr=none_my_server");break;
			case "none_server_reg":header("location: ../login.php?loginerr=none_server_reg");break;
			case "ok":
				header("location: ../login.php?loginerr=ok&shopid=".base64_encode($result['shopid'])."&shopname=".$result['shopname']."&serverid=".$result['serverid']."&roleid=".$result['roleid']."&servername=".$result['servername']."&role=".base64_encode($result['role'])."&logo=".base64_encode($result['logo']));
				break;
		}
	}
	exit;
}

if(isset($_REQUEST['mobilphone'])){
	$mobilphone=$_REQUEST['mobilphone'];
	$passwd=$_REQUEST['password'];
	$phonecrypt= new CookieCrypt($newphonekey);
	$mobilphone=$phonecrypt->encrypt($mobilphone);
	$passwdcrypt= new CookieCrypt($newpwdkey);
	$passwd=$passwdcrypt->encrypt($passwd);
	if(empty($mobilphone)){
		header("location: ../login.php?loginerr=emptyphone");
	}elseif (empty($passwd)){
		header("location: ../login.php?loginerr=emptypwd");
	}else{
		$result=$dologinform->doMobilphoneLogin($mobilphone, $passwd);
		switch ($result['status']){
			case "error":header("location: ../login.php?loginerr=error"); break;
			case "none":header("location: ../login.php?loginerr=none");break;
			case "ok":
				header("location: ../login.php?loginerr=ok&shopid=".base64_encode($result['shopid'])."&shopname=".$result['shopname']."&roleid=".$result['roleid']."&servername=".$result['servername']."&role=".base64_encode($result['role'])."&logo=".base64_encode($result['logo']));
				break;
		}
	
	}
}
?>