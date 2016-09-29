<?php 
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class DoLogin{
	public function DoLoginData($bossphone, $password){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->DoLoginData($bossphone, $password);
	}
}
$dologin=new DoLogin();
if(isset($_POST['bossphone'])){
	$bossphone=$_POST['bossphone'];
	$password=$_POST['password'];
	$phonecrypt = new CookieCrypt($phonekey);
	$pwdcrypt = new CookieCrypt($pwdkey);
	$bossphone=$phonecrypt->encrypt($bossphone);
	$password=$pwdcrypt->encrypt($password);
	$result=$dologin->DoLoginData($bossphone, $password);
// 	var_dump($result);exit;
	if(!empty($result['bossid'])){
		header("location: ../login.php?status=ok&result=".json_encode($result));
	}else{
		header("location: ../login.php?status=error");
	}
		

	
}
?>