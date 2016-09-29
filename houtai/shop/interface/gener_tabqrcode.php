<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class Gener_TabQrcode{
	public function getCheckBillidByShopid($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCheckBillidByShopid($shopid);
	}
	public function sendSelfFormatMessage($msgInfo){
		QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->sendSelfFormatMessage($msgInfo);
	}
	public function generTabQrcodeImg($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->generTabQrcodeImg($inputarr);
	}
}
$gener_tabqrcode=new Gener_TabQrcode();
if(isset($_GET['tabid'])){
	$tabid=$_GET['tabid'];
	$shopid=$_SESSION['shopid'];
	$typeno=$_GET['typeno'];
	$oneprintarr=$gener_tabqrcode->getCheckBillidByShopid($shopid);
	// 	print_r($oneprintarr);exit;
	$inputarr=array();
	if(!empty($oneprintarr)){
		$deviceno=$oneprintarr['deviceno'];
		$devicekey=$oneprintarr['devicekey'];
		$inputarr=array(
				"tabid"=>$tabid,
				"shopid"=>$shopid,
				"deviceno"=>$deviceno,
				"devicekey"=>$devicekey,
		);
	}
	if(!empty($inputarr)){
		$msgInfo=$gener_tabqrcode->generTabQrcodeImg($inputarr);
// 		print_r($msgInfo);exit;
		$gener_tabqrcode->sendSelfFormatMessage($msgInfo);
	}
	header("location: ../tables.php?typeno=$typeno");
}
?>