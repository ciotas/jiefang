<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ShopSwitch{
	public function updateShopSwitch($shopid,$op, $status){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->updateShopSwitch($shopid,$op, $status);
	}
}
$shopswitch=new ShopSwitch();
if(isset($_GET['op'])){
	$shopid=$_SESSION['shopid'];
	$op=$_GET['op'];
	$status=$_GET['status'];
	$shopswitch->updateShopSwitch($shopid, $op, $status);
	echo true;
}
?>