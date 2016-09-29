<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class DoFreePay{
	public function updateFreePayData($inputarr){
		return PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateFreePayData($inputarr);
	}
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
	public function updateOneTabStatus($tabid,$tabstatus){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateOneTabStatus($tabid, $tabstatus);
	}
	public function getTotalmoneyAndFoodDiscountmoney($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTotalmoneyAndFoodDiscountmoney($billid);
	}
	public function getCashierMan($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCashierMan($shopid);
	}
}
$dofreepay=new DoFreePay();
if(isset($_POST['billid'])){
	$billid=$_POST['billid'];
	$shopid=$_POST['shopid'];
	$freename=$_POST['freename'];
	$freereason=$_POST['freereason'];
	$cashierman=$dofreepay->getCashierMan($shopid);
	if(empty($cashierman)){
		$cashierman="趣店账户";
	}
	$foodmoney=$dofreepay->getTotalmoneyAndFoodDiscountmoney($billid);
	$paymoney=$foodmoney['totalmoney'];
	$paymethod="freepay";
	$inputarr=array(
			"billid"=>$billid,
			"paymethod"=>$paymethod,
			"freename"=>$freename,
			"freereason"=>$freereason,
			"freemoney"=>$paymoney,
			"cashierman"=>$cashierman,
	);
// 	print_r($inputarr);exit;
	$dofreepay->updateFreePayData($inputarr);
	$billarr=$dofreepay->getOneBillInfoByBillid($billid);
	$consumeListArr=$dofreepay->tobeConsumeList($billarr,$paymethod,$paymoney);
	// print_r($consumeListArr);exit;//消费清单
	$consumearr=$dofreepay->printConsumeListData(json_encode($consumeListArr));
	if(!empty($consumearr)){$temparr[]=$consumearr;}
	// print_r($consumearr);exit;
	$urls=$dofreepay->getUrlsArr(json_encode($temparr));
// 	print_r($urls);exit;
	$dofreepay->sendFreeMessage($urls);//打印
	$dofreepay->updateOneTabStatus($billarr['tabid'], "empty");//买单之后自动清台
	header("location: ../tabmanage.php");
}
?>