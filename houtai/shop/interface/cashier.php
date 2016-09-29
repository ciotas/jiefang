<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/printbill/Factory/InterfaceFactory.php');
class Cashier{
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
	public function getCusinfo($uid,$shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getCusInfo($uid,$shopid);
	}
	public function getCashierMan($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->getCashierMan($shopid);
	}
	public function getMyvipinfo($shopid,$cardno){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getMyvipinfo($shopid, $cardno);
	}
	public function consumeVipMoney($shopid,$cardno,$money){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->consumeVipMoney($shopid, $cardno, $money);
	}
	public function intoVipConsumeRecord($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->intoVipConsumeRecord($inputarr);
	}
}
$cashier=new Cashier();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];//新增
	$billid=$_POST['billid'];
	$theday=$_POST['theday'];
	$type=$_POST['type'];
	$clearmoney=$_POST['clearmoney'];
	if(empty($clearmoney)){
		$clearmoney="0";
	}
	$discountval=$_POST['discountval'];
	if(empty($discountval)){
		$discountval="100";
	}
	$othermoney=$_POST['othermoney'];
	if(empty($othermoney)){
		$othermoney="0";
	}
	$ticketval=$_POST['ticketval'];
	if(empty($ticketval)){
		$ticketval="0";
	}
	$ticketval=floatval($ticketval);
	$ticketnum=$_POST['ticketnum'];
	if(empty($ticketnum)){
		$ticketnum="0";
	}
	$ticketway=$_POST['ticketway'];
// 	echo $ticketway;exit;
	if(empty($ticketway)){$ticketway="";}
	$paytype1=$_POST['paytype1'];
	$paytype2=$_POST['paytype2'];
	$cardno="";
	$myviparr=array();
	if($paytype1=="vipmoney"){
		$cardno=trim($_POST['cardno']);
		$myviparr=$cashier->getMyvipinfo($shopid, $cardno);
	}
	$anothermoney1=$_POST['anothermoney1'];
	$anothermoney2=$_POST['anothermoney2'];
	if(empty($anothermoney1)){$anothermoney1="0";}
	if(empty($anothermoney2)){$anothermoney2="0";}
	$cuspay=$anothermoney1+$anothermoney2;
	/*
	$cash=$_POST['cash'];
	if(empty($cash)){$cash="0";}
	$anothermoney=$_POST['anothermoney'];
	if(empty($anothermoney)){$anothermoney="0";}
	$cuspay=$anothermoney+$cash;
	*/
	$couponarr=$cashier->getPayPageData($billid, $shopid);
	$allcount=$_POST['allcount'][0];
	if($allcount=="on"){
		$discountmode="all";
		$tfooddisaccountmoney=ceil($couponarr['totalmoney']*(1-$discountval/100));
	}else{
		$discountmode="part";
		$tfooddisaccountmoney=ceil($couponarr['fooddisaccountmoney']*(1-$discountval/100));
	}
	$returndepositmoney=0;
	if(!empty($_POST['returndepositmoney'])){
		$returndepositmoney=$_POST['returndepositmoney'];
	}
	$serverfee=0;
    if(isset($_POST['serverfee'])){
	    $serverfee=$_POST['serverfee'];
	    if(empty($serverfee)){
	        $serverfee="0";
	    }
	}
	$servermoney=$couponarr['fooddisaccountmoney']*($serverfee/100);
	$shouldpay=$couponarr['totalmoney']+$othermoney+$servermoney-$tfooddisaccountmoney-$ticketnum*$ticketval-$clearmoney-$returndepositmoney;
	$shouldpay=round($shouldpay);
// 	if(empty($anothermoney)){
// 		$cashmoney=$shouldpay;
// 	}else{
// 		$cashmoney=$shouldpay-$anothermoney;
// 	}
	$cashmoney="0";
	$unionmoney="0";
	$vipmoney="0";
	$alipay="0";
	$wechatpay="0";
	$meituanpay="0";
	$dazhongpay="0";
	$nuomipay="0";
	$otherpay="0";
	switch ($paytype1){
		case "cashmoney":
		if(empty($anothermoney2)){
			$cashmoney=$shouldpay;
		}else{
			$cashmoney=$shouldpay-$anothermoney2;
		}
		break;
		case "unionmoney":
			if(empty($anothermoney2)){
				$unionmoney=$shouldpay;
			}else{
				$unionmoney=$shouldpay-$anothermoney2;
			}
			break;
		case "vipmoney":
			if(empty($anothermoney2)){
				$vipmoney=$shouldpay;
			}else{
				$vipmoney=$shouldpay-$anothermoney2;
			}
		if(!empty($myviparr)){
			//账户金额变动
			if($vipmoney<=$myviparr['accountbalance']){
				$cashier->consumeVipMoney($shopid, $cardno, $vipmoney);
			}else{
				header("location: ../paypage.php?status=vip_notenough");exit;
			}
			//记录
			$viprecordarr=array(
					"billid"=>$billid,
					"shopid"=>$shopid,
					"uid"=>$myviparr['uid'],
					"cardid"=>$myviparr['cardid'],
					"accountbalance"=>$myviparr['accountbalance']-$vipmoney,
					"consumemoney"=>$vipmoney,
					"timestamp"=>time(),
			);
			$cashier->intoVipConsumeRecord($viprecordarr);
			//发送短信
		}
		break;
		case "alipay":
			if(empty($anothermoney2)){
				$alipay=$shouldpay;
			}else{
				$alipay=$shouldpay-$anothermoney2;
			}
			break;
		case "wechatpay":
			if(empty($anothermoney2)){
				$wechatpay=$shouldpay;
			}else{
				$wechatpay=$shouldpay-$anothermoney2;
			}
			break;
	}
	switch ($paytype2){
		case "alipay":$alipay=$anothermoney2; break;
		case "wechatpay":$wechatpay=$anothermoney2;break;
		case "unionmoney":$unionmoney=$anothermoney2;break;
		case "meituanpay":$meituanpay=$anothermoney2;break;
		case "dazhongpay":$dazhongpay=$anothermoney2;break;
		case "nuomipay":$nuomipay=$anothermoney2;break;
		case "otherpay":$otherpay=$anothermoney2;break;
	}
// 	$cashierman=$cashier->getCashierMan($shopid);
	$cashierman=$_SESSION['servername'];
	$serverid="boss";
	if(!empty($_SESSION['serverid'])){
		$serverid=$_SESSION['serverid'];
	}
	if(empty($cashierman)){
		$cashierman="趣店账户";
	}
	$temparr=array();
	$paymethod="commonpay";
	$totalmoney=0;
	$billarr=$cashier->getOneBillInfoByBillid($billid);
	foreach ($billarr['food'] as $key=>$val){
		if(empty($val['present'])){
			$totalmoney+=$val['foodamount']*$val['foodprice'];
		}
	}
	$paymoney=$totalmoney-$clearmoney;
	$paymethod="commonpay";
	$inputarr=array(
			"billid"=>$billid,
			"cuspay"=>$cuspay,
			"clearmoney"=>$clearmoney,
			"othermoney"=>$othermoney,
			"discountval"=>$discountval,
	        "serverfee"=>$serverfee,
	        "servermoney"=>round($servermoney),
			"cashmoney"=>$cashmoney,
			"unionmoney"=>$unionmoney,
			"vipmoney"=>$vipmoney,
			"discountmode"=>$discountmode,
			"ticketval"=>$ticketval,
			"ticketnum"	=>$ticketnum,
			"ticketway"=>$ticketway,
			"meituanpay"=>$meituanpay,
			"dazhongpay"=>$dazhongpay,
			"nuomipay"=>$nuomipay,
			"otherpay"=>$otherpay,
			"alipay"=>$alipay,
			"wechatpay"=>$wechatpay,
			"returndepositmoney"=>$returndepositmoney,
			"paymethod"=>$paymethod,
			"serverid"=>$serverid,
			"cashierman"=>$cashierman,
	);
// 	print_r($inputarr);exit;
	if($_POST['reprint']=="yes"){
	}else{
		$cashier->updateCommonPayData($inputarr);
	}
	$billarr=$cashier->getOneBillInfoByBillid($billid);//新数据
// 	print_r($billarr);exit;
	$consumeListArr=$cashier->tobeConsumeList($billarr,$paymethod,$paymoney);
// 	print_r($consumeListArr);exit;//消费清单
	$consumearr=$cashier->printConsumeListData(json_encode($consumeListArr));
	if(!empty($consumearr)){$temparr[]=$consumearr;}
	$urls=$cashier->getUrlsArr(json_encode($temparr));
// 	print_r($urls);exit;
	$cashier->sendFreeMessage($urls);//打印
	if($_POST['reprint']=="yes"){
		header("location: ../dailysheet.php?theday=$theday");
	}else{
		if($type=="pay"){
			$cashier->updateOneTabStatus($billarr['tabid'], "empty");//买单之后自动清台
		}
		header("location: ../tabmanage.php");
	}
	
}
?>