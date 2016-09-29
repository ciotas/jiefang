<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class MakeFoodtypeOn{
	public function updateFoodtypeDonateticket($ftid, $donateticket){
		QuDian_InterfaceFactory::createInstanceMonitorThreeDAL()->updateFoodtypeDonateticket($ftid, $donateticket);
	}
}
$makefoodtypeon=new MakeFoodtypeOn();
if(isset($_GET['ftid'])){
	$shopid=$_SESSION['shopid'];
	$ftid=$_GET['ftid'];
	$donateticket=$_GET['status'];
	$makefoodtypeon->updateFoodtypeDonateticket($ftid, $donateticket);
	echo true;
}
?>