<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class GetFoodType{
	public function getFoodtypeByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getFoodtypeByShopid($shopid);
	}
	public function getZoneIdByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getZoneIdByShopid($shopid);
	}
}
$getfoodtype=new GetFoodType();
if(isset($_GET['shopid'])){
	$shopid=$_GET['shopid'];
	$foodtypearr=$getfoodtype->getFoodtypeByShopid($shopid);
	$zonearr=$getfoodtype->getZoneIdByShopid($shopid);
	$arr=array("foodtype"=>$foodtypearr,"zone"=>$zonearr);
	echo json_encode($arr);
}
?>