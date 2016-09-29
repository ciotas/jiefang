<?php 
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class ShopPhoneUse{
	public function isShopPhonereg($phone){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->isShopPhonereg($phone);
	}
}
$shopphoneuse=new ShopPhoneUse();
if(isset($_GET['phone'])){
	$shopphone=$_GET['phone'];
	$phonecrypt = new CookieCrypt($phonekey);
	$shopphone=$phonecrypt->encrypt($shopphone);
	echo $shopphoneuse->isShopPhonereg($shopphone);
}
?>