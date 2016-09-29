<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
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
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getPayPageData($billid, $shopid);
	}
	public function updateOneTabStatus($tabid,$tabstatus){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateOneTabStatus($tabid, $tabstatus);
	}
	public function getCusinfo($uid,$shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getCusInfo($uid,$shopid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
	public function getServeridByUid($shopid,$uid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getServeridByUid($shopid, $uid);
	}
}
$cashier=new Cashier();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];//新增
	$billid=$_POST['billid'];
	$uid=$_POST['uid'];
	$clearmoney=$_POST['clearmoney'];
	$discountval=$_POST['discountval'];
	$ticketval=$_POST['ticketval'];
	$ticketnum=$_POST['ticketnum'];
	$ticketway=$_POST['ticketway'];
	$paytype=$_POST['paytype'];
	$cash=$_POST['cash'];
	$paidorderrequest=$_POST['paidorderrequest'];
	$online=$_POST['online'];
	$anothermoney=$_POST['anothermoney'];
	$allcount=$_POST['allcount'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$cashier->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$billid.$clearmoney.$discountval.$ticketval.$ticketnum.$ticketway.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$cashier->updateCusSession($uid,$session);break;
			}
			$serverid=$cashier->getServeridByUid($shopid, $uid);
			if(empty($serverid)){$serverid="boss";}
			if(empty($clearmoney)){$clearmoney="0";}
			if(empty($discountval)){$discountval="100";}
			if(empty($ticketval)){$ticketval="0";}
			$ticketval=floatval($ticketval);
			if(empty($ticketnum)){$ticketnum="0";}
			if(empty($ticketway)){$ticketway="";}
			if(empty($cash)){$cash="0";}
			if(empty($anothermoney)){$anothermoney="0";}
			$cuspay=$anothermoney+$cash;
			$couponarr=$cashier->getPayPageData($billid, $shopid);
			
			if($allcount=="1"){
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
			$shouldpay=$couponarr['totalmoney']-$tfooddisaccountmoney-$ticketnum*$ticketval-$clearmoney-$returndepositmoney;
			$shouldpay=round($shouldpay);
			if(empty($anothermoney)){
				$cashmoney=$shouldpay;
			}else{
				$cashmoney=$shouldpay-$anothermoney;
			}
			$unionmoney="0";
			$vipmoney="0";
			$alipay="0";
			$wechatpay="0";
			$meituanpay="0";
			$dazhongpay="0";
			$nuomipay="0";
			$otherpay="0";
			switch ($paytype){
				// 		case "cashmoney":$cashmoney=$shouldpay; break;
				case "unionmoney":$unionmoney=$anothermoney;break;
				case "vipmoney":$vipmoney=$anothermoney;break;
				case "alipay":$alipay=$anothermoney; 
// 				if(empty($serverid)){$serverid="customer";}
				break;
				case "wechatpay":$wechatpay=$anothermoney;
// 				if(empty($serverid)){$serverid="customer";}
				break;
				case "meituanpay":$meituanpay=$anothermoney;break;
				case "dazhongpay":$dazhongpay=$anothermoney;break;
				case "nuomipay":$nuomipay=$anothermoney;break;
				case "otherpay":$otherpay=$anothermoney;break;
			}
			$userarr=$cashier->getCusinfo($uid,$shopid);
			if(empty($userarr)){
				$cashierman="趣店账户";
			}else{
				$cashierman=$userarr['nickname'];
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
					"othermoney"=>"0",
					"discountval"=>$discountval,
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
			        "paidorderrequest"=>$paidorderrequest,
			);
			
			$cashier->updateCommonPayData($inputarr);
			$billarr=$cashier->getOneBillInfoByBillid($billid);//新数据
			// 	print_r($billarr);exit;
			$consumeListArr=$cashier->tobeConsumeList($billarr,$paymethod,$paymoney);
			// 	print_r($consumeListArr);exit;//消费清单
			$consumearr=$cashier->printConsumeListData(json_encode($consumeListArr));
			if(!empty($consumearr)){$temparr[]=$consumearr;}
			$urls=$cashier->getUrlsArr(json_encode($temparr));
			// 	print_r($urls);exit;
			$cashier->sendFreeMessage($urls);//打印
			if(!empty($online)){
			    $tabstatus="online";
			}else{
			    $tabstatus="empty";
			}
			$cashier->updateOneTabStatus($billarr['tabid'], $tabstatus);
			header('Content-type: application/json');
			echo json_encode(array("status"=>"ok"));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$billid="5654594d5bc109aa5c8b5191";
$shopid="554ad9615bc109d8518b45d2";
$uid="554ad8cc5bc109d7518b45b5";
$cuspay="100";
$clearmoney="0";
$othermoney="0";
$discountval="89";
$cashmoney="68";
$unionmoney="0";
$vipmoney="0";
$ticketval="10";
$ticketnum="1";
$ticketway="554b03695bc109dd518b45c2";
$meituanpay="0";
$dazhongpay="0";
$nuomipay="0";
$otherpay="0";
$alipay="0";
$paytype="unionpay";
$wechatpay="0";
$allcount="1";
$returndepositmoney="0";
if(empty($clearmoney)){$clearmoney="0";}
if(empty($discountval)){$discountval="100";}
if(empty($ticketval)){$ticketval="0";}
$ticketval=floatval($ticketval);
if(empty($ticketnum)){$ticketnum="0";}
if(empty($ticketway)){$ticketway="";}
if(empty($cash)){$cash="0";}
if(empty($anothermoney)){$anothermoney="0";}
$cuspay=$anothermoney+$cash;
$couponarr=$cashier->getPayPageData($billid, $shopid);
// print_r($couponarr);exit;
if($allcount=="1"){
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
$shouldpay=$couponarr['totalmoney']-$tfooddisaccountmoney-$ticketnum*$ticketval-$clearmoney-$returndepositmoney;
$shouldpay=round($shouldpay);
if(empty($anothermoney)){
	$cashmoney=$shouldpay;
}else{
	$cashmoney=$shouldpay-$anothermoney;
}
$unionmoney="0";
$vipmoney="0";
$alipay="0";
$wechatpay="0";
$meituanpay="0";
$dazhongpay="0";
$nuomipay="0";
$otherpay="0";
switch ($paytype){
	// 		case "cashmoney":$cashmoney=$shouldpay; break;
	case "unionmoney":$unionmoney=$anothermoney;break;
	case "vipmoney":$vipmoney=$anothermoney;break;
	case "alipay":$alipay=$anothermoney; break;
	case "wechatpay":$wechatpay=$anothermoney;break;
	case "meituanpay":$meituanpay=$anothermoney;break;
	case "dazhongpay":$dazhongpay=$anothermoney;break;
	case "nuomipay":$nuomipay=$anothermoney;break;
	case "otherpay":$otherpay=$anothermoney;break;
}
$userarr=$cashier->getCusinfo($uid,$shopid);
// print_r($userarr);exit;
if(empty($userarr)){
	$cashierman="趣店账户";
}else{
	$cashierman=$userarr['nickname'];
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
		"othermoney"=>"0",
		"discountval"=>$discountval,
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
		"cashierman"=>$cashierman,
);
// print_r($inputarr);exit;
// $cashier->updateCommonPayData($inputarr);
$billarr=$cashier->getOneBillInfoByBillid($billid);//新数据
// 	print_r($billarr);exit;
$consumeListArr=$cashier->tobeConsumeList($billarr,$paymethod,$paymoney);
// 	print_r($consumeListArr);exit;//消费清单
$consumearr=$cashier->printConsumeListData(json_encode($consumeListArr));
if(!empty($consumearr)){$temparr[]=$consumearr;}
$urls=$cashier->getUrlsArr(json_encode($temparr));
	print_r($urls);exit;

?>