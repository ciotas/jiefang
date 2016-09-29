<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AddPicAddr{
	public function updatePicAddress($foodid, $foodpic){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->updatePicAddress($foodid, $foodpic);
	}
	public function syncData($shopid){
	    QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->syncData($shopid);
	}
}
$addpicaddr=new AddPicAddr();
if(isset($_POST['foodid'])){
	$shopid=$_SESSION['shopid'];
	$foodid=$_POST['foodid'];
	$sortno=$_POST['sortno'];
	$foodpic=$_POST['foodpic'];
	$addpicaddr->updatePicAddress($foodid, $foodpic);
	$addpicaddr->syncData($shopid);
	header("location: ../upfoodpic.php?sortno=$sortno");
}
?>