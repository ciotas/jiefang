<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneShop{
	public function delOneShopData($shopid){
		Boss_InterfaceFactory::createInstanceBossOneDAL()->delOneShopData($shopid);
	}
}
$deloneshop=new DelOneShop();
if(isset($_GET['shopid'])){
	$shopid=base64_decode($_GET['shopid']);
	$deloneshop->delOneShopData($shopid);
	header("location: ../shoplist.php");
}
?>