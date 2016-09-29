<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class ReturnFood{
	public function updateBillFood($foodarr,$billid, $returnnum,$foodid,$foodnum,$cooktype){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateBillFood($foodarr,$billid, $returnnum, $foodid,$foodnum,$cooktype);
	}
	public function getFoodsByBillid($billid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodsByBillid($billid);
	}
	public function getOneFoodInBill($billid, $foodid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneFoodInBill($billid, $foodid);
	}
	public function printReturnOrder($inputarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->printReturnOrder($inputarr);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function addToReturnBill($inputarr){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addToReturnBill($inputarr);
	}
}
$returnfood=new ReturnFood();
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$foodarr=$returnfood->getFoodsByBillid($billid);
	$returnnum=$_GET['returnnum'];
	$foodid=$_GET['foodid'];
	$foodnum=$_GET['foodnum'];
	$cooktype=$_GET['cooktype'];
	$billarr=$returnfood->getOneFoodInBill($billid,$foodid);
	
	//打印退菜单
	$inputarr=array(
			"uid"=>$billarr['uid'],
			"nickname"=>$billarr['nickname'],
			"tabname"=>$billarr['tabname'],
			"billid"=>$billid,
			"foodid"=>$foodid,
			"cusnum"=>$billarr['cusnum'],
			"foodnum"=>$billarr['foodnum'],
			"orderunit"=>$billarr['orderunit'],
			"foodname"=>$billarr['foodname'],
			"returnnum"=>$returnnum,
			"timestamp"=>time(),
	);
	
	$printarr=$returnfood->printReturnOrder($inputarr);
	$returnfood->updateBillFood($foodarr, $billid, $returnnum, $foodid, $foodnum, $cooktype);
	$returnfood->sendFreeMessage($printarr);//打印
	$returnfood->addToReturnBill($inputarr);//添加退菜记录
	header("location: ../tabmanage.php");exit;
	
}
?>