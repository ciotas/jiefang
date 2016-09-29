<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class PrintPrePay{
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
$printprepay=new PrintPrePay();
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$billarr=$printprepay->getOneBillInfoByBillid($billid);
	foreach ($billarr['food'] as $key=>$val){
		if(empty($val['present'])){
			$totalmoney+=$val['foodamount']*$val['foodprice'];
		}
	}
	$paymoney=$totalmoney;
	$paymethod="commonpay";
	$consumeListArr=$printprepay->tobeConsumeList($billarr,$paymethod,$paymoney);
	// 	print_r($consumeListArr);exit;//消费清单
	$consumearr=$printprepay->printConsumeListData(json_encode($consumeListArr));
	if(!empty($consumearr)){$temparr[]=$consumearr;}
	// 	print_r($temparr);exit;
	$urls=$printprepay->getUrlsArr(json_encode($temparr));
	// 	print_r($urls);exit;
	$printprepay->sendFreeMessage($urls);//打印
	header("location: ../tabmanage.php");
}
?>