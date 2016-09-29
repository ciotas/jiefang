<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class SureTakeout{
	public function updateTakeoutSheet($billid,$op){
		QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->updateTakeoutSheet($billid, $op);
	}
	public function tobeRunner($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeRunner($inputdarr);
	}
	public function printChuanCaiData($json){
		return PRINT_InterfaceFactory::createInstanceRunnerWorkerDAL()->printChuanCaiData($json);
	}
	public function getUrlsArr($json){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function tobeCusList($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeCusList($inputdarr);
	}
	public function tobeConsumeList($inputdarr,$paymethod,$paymoney){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr,$paymethod,$paymoney);
	}
	public function printConsumeListData($json){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json);
	}
	public function orderByprinterid($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->orderByprinterid($inputdarr);
	}
	public function tobePieceList($arr){
		return PRINT_InterfaceFactory::createInstancePieceListDAL()->tobePieceList($arr);
	}
	public function PrintKitchenData($json){
		return PRINT_InterfaceFactory::createInstanceKitchenWorkerDAL()->PrintKitchenData($json);
	}
	public function printCuslistData($json){
		return PRINT_InterfaceFactory::createInstanceCusListWorkerDAL()->printCuslistData($json);
	}
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
}
$suretakeout=new SureTakeout();
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$op=$_GET['op'];
	$theday=$_GET['theday'];
	$suretakeout->updateTakeoutSheet($billid, $op);
	if($op=="sure"){
		$billarr=$suretakeout->getOneBillInfoByBillid($billid);
		$foodRunnerArr=$suretakeout->tobeRunner($billarr);//传菜单
		// print_r($foodRunnerArr);exit;
		$chuancaiarr=$suretakeout->printChuanCaiData(json_encode($foodRunnerArr));//pass
		if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
		//厨房单
		$billarr['printerid']="";//代表按照规则打印
		$orderfoodarr=$suretakeout->orderByprinterid($billarr);
		// print_r($orderfoodarr);exit;
		$piecelistArr=$suretakeout->tobePieceList($orderfoodarr);
		// print_r($piecelistArr);exit;
		$kitchenarr=$suretakeout->PrintKitchenData(json_encode($piecelistArr));
		if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
		// print_r($kitchenarr);exit;
		$urls=$suretakeout->getUrlsArr(json_encode($temparr));
		$suretakeout->sendFreeMessage($urls);
	}
	
	header("location: ../takeoutsheet.php?theday=".$theday);
}
	
?>