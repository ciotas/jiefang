<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class DoCusVipPay{
	public function updateMyvipAccount($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->updateMyvipAccount($inputarr);
	}
	public function vipPayrecord($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->vipPayrecord($inputarr);
	}
	public function getPreBillByBillid($billid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getPreBillByBillid($billid);
	}
	public function getShopInfo($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getShopInfo($shopid);
	}
	public function updateCommonPayData($inputarr){
		return PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateCommonPayData($inputarr);
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
	public function getPayPageData($billid, $shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getPayPageData($billid, $shopid);
	}
	public function updateOneTabStatus($tabid,$tabstatus){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateOneTabStatus($tabid, $tabstatus);
	}
	public function delPrebillByBillid($billid){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->delPrebillByBillid($billid);
	}
	public function addPayRecord($inputarr){
		QuDian_InterfaceFactory::createInstancePayDAL()->addPayRecord($inputarr);
	}
}
$docusvippay=new DoCusVipPay();
if(isset($_GET['billid'])){
	$billid=$_GET['billid'];
	$uid=$_GET['uid'];
	$shopid=$_GET['shopid'];
	$shouldpay=$_GET['shouldpay'];
	$accountbalance=$_GET['accountbalance'];
	$discounmoney=$_GET['discounmoney'];
	$carddiscount=$_GET['carddiscount'];
	$deposit=$_GET['deposit'];
	$nickname=$_GET['nickname'];
	$inputarr=array(
			"billid"=>$billid,
			"uid"=>$uid,
			"shopid"=>$shopid,
			"shouldpay"=>$shouldpay,
			"accountbalance"	=>$accountbalance,
			"discounmoney"=>$discounmoney,
	);
// 	print_r($inputarr);exit;
	
	$docusvippay->updateMyvipAccount($inputarr);
	$docusvippay->vipPayrecord($inputarr);
	
	//收银
	
	$shoparr=$docusvippay->getShopInfo($shopid);
	if($deposit=="1"){
		$returndepositmoney=$shoparr['depositmoney'];
	}
	$prearr=$docusvippay->getPreBillByBillid($billid);
	$paymethod="vippay";
	
	$clearmoney="0";
	$ticketval="0";
	$ticketnum="0";
	$ticketway="";
	$discountval="100";
	$discountmode="part";
	$returndepositmoney="0";
	if(!empty($prearr)){
		$clearmoney=$prearr['clearmoney'];
		if($prearr['allcount']=="1"){
			$discountmode="all";
		}else{
			$discountmode="part";
		}
		$ticketval=$prearr['ticketval'];
		$ticketnum=$prearr['ticketnum'];
		$ticketway=$prearr['ticketway'];
		$returndepositmoney=$prearr['returndepositmoney'];
		$discountval=$prearr['discountval'];
	}
	if(empty($discountval) || $discountval=="100"){
		$discountval=$carddiscount;
	}
	$cuspay=$shouldpay-$discounmoney; 
	$paymoney=$cuspay;
	
	$inputarr=array(
			"billid"=>$billid,
			"cuspay"=>$cuspay,
			"clearmoney"=>$clearmoney,
			"othermoney"=>"0",
			"discountval"=>$discountval,
			"cashmoney"=>"0",
			"unionmoney"=>"0",
			"vipmoney"=>$cuspay,
			"discountmode"=>$discountmode,
			"ticketval"=>$ticketval,
			"ticketnum"	=>$ticketnum,
			"ticketway"=>$ticketway,
			"meituanpay"=>"0",
			"dazhongpay"=>"0",
			"nuomipay"=>"0",
			"alipay"=>"0",
			"wechatpay"=>"0",
			"returndepositmoney"=>$returndepositmoney,
			"paymethod"=>$paymethod,
			"cashierman"=>$nickname,
	);
// 	print_r($inputarr);exit;
	$docusvippay->updateCommonPayData($inputarr);
	$billarr=$docusvippay->getOneBillInfoByBillid($billid);//新数据
	$consumeListArr=$docusvippay->tobeConsumeList($billarr,$paymethod,$paymoney);
// 	print_r($consumeListArr);exit;//消费清单
	$consumearr=$docusvippay->printConsumeListData(json_encode($consumeListArr));
	if(!empty($consumearr)){$temparr[]=$consumearr;}
	$urls=$docusvippay->getUrlsArr(json_encode($temparr));
// 	print_r($urls);exit;
	$docusvippay->sendFreeMessage($urls);//打印
	$docusvippay->updateOneTabStatus($billarr['tabid'], "empty");//买单之后自动清台
	$docusvippay->delPrebillByBillid($billid);
	
	$out_trade_no=time().mt_rand(1000, 9999);
	$payrecord=array(
			"out_trade_no"=>$out_trade_no,
			"trade_no"=>"0",
			"billid"	=>$billid,
			"shopid"=>$billarr['shopid'],
			"uid"=>$billarr['uid'],
			"buyer"=>$nickname,
			"tabid"=>$billarr['tabid'],
			"paymoney"=>$paymoney,
			"paytype"=>"vippay",
			"downtime"=>$billarr['timestamp'],
			"buyemail"=>"",
			"buytime"=>time(),
				
	);
	$docusvippay->addPayRecord($payrecord);
	header("location: ../vippayresult.php");
	
	
}
?>