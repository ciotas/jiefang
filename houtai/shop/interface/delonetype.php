<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneFoodtype{
	public function delOneFoodTypeData($ftid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->delOneFoodTypeData($ftid);
	}
	public function syncData($shopid){
	    QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->syncData($shopid);
	}
}
$delonefoodtype=new DelOneFoodtype();
if(isset($_GET['ftid'])){
    $shopid=$_SESSION['shopid'];
	$ftid=base64_decode($_GET['ftid']);
	$result=$delonefoodtype->delOneFoodTypeData($ftid);
	$delonefoodtype->syncData($shopid);
	header("location: ../foodtype.php?res=$result");
}
?>