<?php 
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class DoReg{
	public function regBossAccount($bossphone,$checkcode,$bossname,$passwd,$addtime){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->regBossAccount($bossphone, $checkcode, $bossname, $passwd, $addtime);
	}
}
$doreg=new DoReg();
if(isset($_POST['bossphone'])){
	$bossphone=$_POST['bossphone'];
	$checkcode=$_POST['checkcode'];
	$bossname=$_POST['bossname'];
	$passwd=$_POST['password'];
	$registertime=time();
	$phonecrypt= new CookieCrypt($phonekey);
	$bossphone=$phonecrypt->encrypt($bossphone);
	$pwdcrypt= new CookieCrypt($pwdkey);
	$passwd=$pwdcrypt->encrypt($passwd);
// 	$inputarr=array(
// 			"bossphone"=>$bossphone,
// 			"checkcode"=>$checkcode,
// 			"bossname"=>$bossname,
// 			"password"=>$passwd,
// 			"registertime"=>$registertime,
			
// 	);
// 	print_r($inputarr);exit;
	$result=$doreg->regBossAccount($bossphone, $checkcode, $bossname,$passwd, $registertime);
	header("location: ../login.php");
}
?>