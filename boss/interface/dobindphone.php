<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class DoBindPhone{
	public function bindShopPhoneData($inputarr){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->bindShopPhoneData($inputarr);
	}
}
$dobindphone=new DoBindPhone();
$bossid=$_SESSION['bossid'];
if(isset($_POST['shopphone'])){
	$shopphone=$_POST['shopphone'];
	$checkcode=$_POST['checkcode'];
	$phonecrypt = new CookieCrypt($phonekey);
	$shopphone=$phonecrypt->encrypt($shopphone);
	$inputarr=array(
			"bossid"=>$bossid,
			"shopphone"	=>$shopphone,
			"checkcode"=>$checkcode,
			"timestamp"=>time(),
	);
// 	print_r($inputarr);exit;
	$result=$dobindphone->bindShopPhoneData($inputarr);
	header("location: ../shoplist.php");
}

?>