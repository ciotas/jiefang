<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class DoSignpay{
	public function updateSignPayData($inputarr){
		return PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateSignPayData($inputarr);
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
$dosignpay=new DoSignpay();
if(isset($_POST['signername'])){
	$signername=$_POST['signername'];
	$signerunit=$_POST['signerunit'];
	$shopid=$_SESSION['shopid'];
	$billid=$_POST['billid'];
	$cashierman=$dosignpay->getCashierMan($shopid);
	if(empty($cashierman)){
		$cashierman="趣店账户";
	}
	$foodmoney=$dosignpay->getTotalmoneyAndFoodDiscountmoney($billid);
	$paymoney=$foodmoney['totalmoney'];
	$paymethod="signpay";
	$inputarr=array(
			"billid"=>$billid,
			"paymethod"=>$paymethod,
			"signername"=>$signername,
			"signerunit"=>$signerunit,
			"signmoney"=>$paymoney,
			"cashierman"=>$cashierman,
	);
// 	print_r($inputarr);exit;
	$dosignpay->updateSignPayData($inputarr);
	$billarr=$dosignpay->getOneBillInfoByBillid($billid);
	$consumeListArr=$dosignpay->tobeConsumeList($billarr,$paymethod,$paymoney);
	// print_r($consumeListArr);exit;//消费清单
	$consumearr=$dosignpay->printConsumeListData(json_encode($consumeListArr));
	if(!empty($consumearr)){$temparr[]=$consumearr;}
	// print_r($consumearr);exit;
	$urls=$dosignpay->getUrlsArr(json_encode($temparr));
// 	print_r($urls);exit;
	$dosignpay->sendFreeMessage($urls);//打印
	$dosignpay->updateOneTabStatus($billarr['tabid'], "empty");//买单之后自动清台
	header("location: ../tabmanage.php");
}
?>