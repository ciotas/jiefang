<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class ShopSwitch{
	public function updateShopSwitch($shopid, $type, $status){
		Admin_InterfaceFactory::createInstanceAdminOneDAL()->updateShopSwitch($shopid, $type, $status);
	}
}
$shopswitch=new ShopSwitch();
if(isset($_GET['shopid'])){
	$shopid=$_GET['shopid'];
	$type=$_GET['type'];
	$status=$_GET['status'];
	$shopswitch->updateShopSwitch($shopid, $type, $status);
	echo true;
}
?>