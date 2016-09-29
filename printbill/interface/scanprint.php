<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class ScanPrint{
	public function updateOneTabStatus($tabid,$tabstatus){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateOneTabStatus($tabid, $tabstatus);
	}
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function tobeRunner($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeRunner($inputdarr);
	}
	public function printChuanCaiData($json){
		return PRINT_InterfaceFactory::createInstanceRunnerWorkerDAL()->printChuanCaiData($json);
	}
	public function tobeCusList($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeCusList($inputdarr);
	}
	public function printCuslistData($json){
		return PRINT_InterfaceFactory::createInstanceCusListWorkerDAL()->printCuslistData($json);
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
	public function getUrlsArr($json){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function getMustOrderMenuData($shopid,$cusnum){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getMustOrderMenuData($shopid, $cusnum);
	}
	public function addOrderToOldBill($oldbillid, $foodarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addOrderToOldBill($oldbillid, $foodarr);
	}
	public function updateBillData($inputarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateBillData($inputarr);
	}
	public function getBillAndPayStatus($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillAndPayStatus($billid);
	}
	public function getBillinfoByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillinfoByTabid($tabid);
	}
	public function delOneBerforeBill($billid){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->delOneBerforeBill($billid);
	}
	public function addUidToBill($billid,$uid){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addUidToBill($billid, $uid);
	}
	public function getOneBillInfoByBeforeBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBeforeBillid($billid);
	}
	public function intoConsumeRecord($inputdarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->intoConsumeRecord($inputdarr);
	}
	public function getTabStatusByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTabStatusByTabid($tabid);
	}
}
$scanprint=new ScanPrint();
if(isset($_REQUEST['billid'])){
	$billid=$_REQUEST['billid'];
	$tabid=$_REQUEST['tabid'];
	$type=$_REQUEST['type'];
	$timestamp=$_REQUEST['timestamp'];
	$signature=$_REQUEST['signature'];
	$ownsignature=md5($billid.$tabid.$timestamp."560ffb637cc109");
	if($signature==$ownsignature){
		$tabstatus=$scanprint->getTabStatusByTabid($tabid);
		//多个人点餐
		if($tabstatus=="start" || $tabstatus=="online"){
			$prebillarr=$scanprint->getBillinfoByTabid($tabid);
			$ownbillarr=$scanprint->getOneBillInfoByBeforeBillid($billid);
			$scanprint->addOrderToOldBill($prebillarr['billid'], $ownbillarr['food']);//点的餐加入原来的桌台
			$billarr=$scanprint->getOneBillInfoByBillid($prebillarr['billid']);
			$billarr['food']=$ownbillarr['food'];
			$billarr['nickname']=$ownbillarr['nickname'];
			//添加uid
			$scanprint->addUidToBill($prebillarr['billid'], $ownbillarr['uid']);
		}elseif($tabstatus=="empty" || $tabstatus=="book"){
			$billstatusarr=$scanprint->getOneBillInfoByBeforeBillid($billid);
			$newbillid=$scanprint->intoConsumeRecord($billstatusarr);
			$scanprint->updateOneTabStatus($tabid, "start");//开台
			$mustfoodarr=array();
			if($billstatusarr['billstatus']=="undone"){
				$mustfoodarr=$scanprint->getMustOrderMenuData($billstatusarr['shopid'], $billstatusarr['cusnum']);//必点菜
			}
			if(!empty($mustfoodarr)){
				$scanprint->addOrderToOldBill($newbillid, $mustfoodarr);
			}
			$scanprint->updateBillData(array("billid"=>$newbillid,"billstatus"=>"done","tabid"=>$tabid));
			$billarr=$scanprint->getOneBillInfoByBillid($newbillid);
			$billarr['nickname']=$billstatusarr['nickname'];
			$scanprint->addUidToBill($newbillid, $billstatusarr['uid']);
		}
		$scanprint->delOneBerforeBill($billid);//删除预点单
		/************************第一次下单打印场景*******************************************/
		$foodRunnerArr=$scanprint->tobeRunner($billarr);//传菜单
		$chuancaiarr=$scanprint->printChuanCaiData(json_encode($foodRunnerArr));//pass
		if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
		
		$cusListArr=$scanprint->tobeCusList($billarr);//划菜单
		$cuslistarr=$scanprint->printCuslistData(json_encode($cusListArr));//menu
		if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
		//厨房单
		$billarr['printerid']="";//代表按照规则打印
		$orderfoodarr=$scanprint->orderByprinterid($billarr);
		$piecelistArr=$scanprint->tobePieceList($orderfoodarr);
		$kitchenarr=$scanprint->PrintKitchenData(json_encode($piecelistArr));
		if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
		$urls=$scanprint->getUrlsArr(json_encode($temparr));
		$scanprint->sendFreeMessage($urls);
// 		header("location: ".$root_url."houtai/shop/donesuccess.php?shopid=".$billarr['shopid']."&type=$type");
		header('Content-type: application/json');
		echo json_encode(array("status"=>"ok", "shopid"=>$billarr['shopid'],"type"=>$type));
		exit;
	}else{
		header('Content-type: application/json');
		echo json_encode(array("status"=>"error"));
	}
}
?>