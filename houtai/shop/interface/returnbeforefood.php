<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class ReturnFood{
	public function updateBeforeBillFood($foodarr,$billid, $returnnum,$foodid,$foodnum,$cooktype){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateBeforeBillFood($foodarr,$billid, $returnnum, $foodid,$foodnum,$cooktype);
	}
	public function updateBillFood($foodarr,$billid, $returnnum,$foodid,$foodnum,$cooktype){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateBillFood($foodarr,$billid, $returnnum, $foodid,$foodnum,$cooktype);
	}
	public function getFoodsByBeforeBillid($billid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getFoodsByBeforeBillid($billid);
	}
	public function getOneFoodInBeforeBill($billid, $foodid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneFoodInBeforeBill($billid, $foodid);
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
	$foodarr=$returnfood->getFoodsByBeforeBillid($billid);
	$returnnum=$_GET['returnnum'];
	$foodid=$_GET['foodid'];
	$foodnum=$_GET['foodnum'];
	$cooktype=$_GET['cooktype'];
	$loc=$_GET['loc'];
	$op=$_GET['op'];
	$from=$_GET['from'];
	$uid=$_GET['uid'];
	$paystatus=$_GET['paystatus'];
	$billstatus=$_GET['billstatus'];
	$billarr=$returnfood->getOneFoodInBeforeBill($billid,$foodid);
	$returnfood->updateBeforeBillFood($foodarr, $billid, $returnnum, $foodid, $foodnum, $cooktype);

	if($loc=="prebill"){
		header("location: ../prebill.php?billid=$billid&op=$op");
		exit;
	}elseif($loc=="onebeforebill"){
		if($op=="inhouse"){
			$takeout="0";
		}elseif($op=="takeout"){
			$takeout="1";
		}
		header("location: ../onebeforebill.php?billid=$billid&takeout=$takeout&paystatus=$paystatus&billstatus=$billstatus&from=$from&uid=$uid");
		exit;
	}
		
}
?>