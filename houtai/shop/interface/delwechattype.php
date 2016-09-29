<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class DelWechatFoodtype{
	public function delOneFoodTypeData($ftid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->delOneFoodTypeData($ftid);
	}
	public function syncData($shopid){
	    QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->syncData($shopid);
	}
	public function getShopidByOpenid($openid){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopidByOpenid($openid);
	}
}
$delonefoodtype=new DelWechatFoodtype();
if(isset($_GET['ftid'])){
    $openid=$_GET['openid'];
    $shopid=$delonefoodtype->getShopidByOpenid($openid);
	$ftid=base64_decode($_GET['ftid']);
	$result=$delonefoodtype->delOneFoodTypeData($ftid);
	$delonefoodtype->syncData($shopid);
	header("location: ../wechatservice/foodtype.php?res=$result&openid=$openid");
}
?>