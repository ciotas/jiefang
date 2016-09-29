<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelOneFood{
	public function delOneFoodData($foodid){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->delOneFoodData($foodid);
	}
	public function syncData($shopid){
	    QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->syncData($shopid);
	}
}
$delonefood=new DelOneFood();
if(isset($_GET['foodid'])){
    $openid=$_GET['openid'];
	$shopid=$_GET['shopid'];
	$foodid=base64_decode($_GET['foodid']);
	$typeno=$_GET['typeno'];
	$delonefood->delOneFoodData($foodid);
	$delonefood->syncData($shopid);
	header("location: ../wechatservice/foodmanage.php?typeno=".$typeno."&openid=$openid");
}
?>
