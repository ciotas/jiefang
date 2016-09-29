<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class PrintTakeout{
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function tobeConsumeList($inputdarr,$paymethod,$paymoney){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr,$paymethod,$paymoney);
	}
	public function printConsumeListData($json){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json);
	}
	public function getUrlsArr($json){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
}
$printtakeout=new PrintTakeout();
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$theday=$_GET['theday'];
	$billarr=$printtakeout->getOneBillInfoByBillid($billid);
	$paymethod="commonpay";
	$paymoney="0";
	$consumeListArr=$printtakeout->tobeConsumeList($billarr,$paymethod,$paymoney);
	// print_r($consumeListArr);exit;//消费单，不是结账单
	$consumearr=$printtakeout->printConsumeListData(json_encode($consumeListArr));
	if(!empty($consumearr)){$temparr[]=$consumearr;}
	$urls=$printtakeout->getUrlsArr(json_encode($temparr));
	$printtakeout->sendFreeMessage($urls);
	header("location: ../takeoutsheet.php?theday=$theday");
}
?>