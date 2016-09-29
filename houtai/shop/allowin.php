<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class AllowIn{
	public function getAllowinbalanceValue($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getAllowinbalanceValue($shopid);
	}
}
$allowin=new AllowIn();
$shopid=$_SESSION['shopid'];
$allowinbalance=$allowin->getAllowinbalanceValue($shopid);
if($allowinbalance=="1"){
}else{
	header("location: $base_url");
}
?>