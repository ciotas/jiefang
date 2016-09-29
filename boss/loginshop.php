<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/global.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class LoginShop{
	public function getShopinfoByShopid($shopid){
		return Boss_InterfaceFactory::createInstanceBossOneDAL()->getShopinfoByShopid($shopid);
	}
}
$loginshop=new LoginShop();
if(isset($_GET['shopid'])){
	$shopid=$_GET['shopid'];
	$arr=$loginshop->getShopinfoByShopid($shopid);
	header("location: ".ROOTURL."houtai/shop/interface/dologin.php?mobilphone=".$arr['mobilphone']."&password=".$arr['passwd']);
}
?>