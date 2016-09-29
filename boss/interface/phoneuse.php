<?php 
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class PhoneUse{
	public function isPhoneUse($phone){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->isPhoneUse($phone);
	}
}
$phoneuse=new PhoneUse();
if(isset($_GET['phone'])){
	$bossphone=$_GET['phone'];
	$phonecrypt = new CookieCrypt($phonekey);
	$bossphone=$phonecrypt->encrypt($bossphone);
	echo $phoneuse->isPhoneUse($bossphone);
}
?>