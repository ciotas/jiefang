<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class ServerPrintBill{
	public function getTablenameByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTablenameByTabid($tabid);
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
	public function intoConsumeRecord($inputdarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->intoConsumeRecord($inputdarr);
	}
	public function updateOneTabStatus($tabid,$tabstatus){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateOneTabStatus($tabid, $tabstatus);
	}
	public function getCusinfo($uid,$shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getCusInfo($uid,$shopid);
	}
	public function getShopInfo($shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getShopInfo($shopid);
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
	public function getTabStatusByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTabStatusByTabid($tabid);
	}
	public function getLastbillidByTabid($shopid,$tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getLastbillidByTabid($shopid, $tabid);
	}
	public function tobeConsumeList($inputdarr,$paymethod,$paymoney){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr,$paymethod,$paymoney);
	}
	public function printConsumeListData($json){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json);
	}
	public function printPrePaySheet($json,$op){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printPrePaySheet($json,$op);
	}
	public function printCuslistData($json){
		return PRINT_InterfaceFactory::createInstanceCusListWorkerDAL()->printCuslistData($json);
	}
	public function getTheTabPaid($tabid, $shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTheTabPaid($tabid, $shopid);
	}
	public function createVirtualTab($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->createVirtualTab($tabid);
	}
	public function getTabInfoByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTabInfoByTabid($tabid);
	}
	public function getDonateInfoByShopid($shopid,$foodarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getDonateInfoByShopid($shopid, $foodarr);
	}
	public function getBillPackarr($billid, $package){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillPackarr($billid, $package);
	}
	public function addOrderToOldBill($oldbillid, $foodarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addOrderToOldBill($oldbillid, $foodarr);
	}
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function updateFoodsToBill($billid, $foodarr){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateFoodsToBill($billid, $foodarr);
	}
	public function updateCommonPayData($inputarr){
		PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateCommonPayData($inputarr);
	}
	public function addBillRecordData($inputarr){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addBillRecordData($inputarr);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$serverprintbill=new ServerPrintBill();
if(isset($_POST['shopid']) && isset($_POST['uid'])){
	$shopid=$_POST['shopid'];
	$uid=$_POST['uid'];
	$orderno=$_POST['orderno'];//订单号
	$tradeno=$_POST['tradeno'];//交易号
	$tabid=$_POST['tabid'];
	$wait=$_POST['wait'];
	$billid=$_POST['billid'];//加菜时不为空
	$takeout=$_POST['takeout'];
	$addorder=$_POST['addorder'];
	$invoice=$_POST['invoice'];
	$orderrequest=$_POST['orderrequest'];
	$takeoutaddress=$_POST['takeoutaddress'];
	$discountype=$_POST['discountype'];//none 无优惠
	$paystatus=$_POST['paystatus'];//付款状态，未付款unpay,已付款paid
	$paytype=$_POST['paytype'];//服务员下单serverpay，顾客线上下单alipay,wechatpay ,unionpay 线下付款 offlinepay
	$cusnum=$_POST['cusnum'];
	$deposit=$_POST['deposit'];
	$paymoney=$_POST['paymoney'];
	$food=$_POST['food'];
	$package=$_POST['package'];
	 if($shopid=="5514c2c416c1092b2f8b4594"){
                file_put_contents('/var/www/html/printbill/log/postdata.log',time(),FILE_APPEND);
                file_put_contents('/var/www/html/printbill/log/postdata.log',"\r\n",FILE_APPEND);
                file_put_contents('/var/www/html/printbill/log/postdata.log',$package,FILE_APPEND);
                file_put_contents('/var/www/html/printbill/log/postdata.log',"\r\n",FILE_APPEND);
        }

	$paystate =$_POST['paystate'];//餐前付款
	$op=$_POST['op'];//餐前付款方式
	$timestamp=$_POST['timestamp'];//下单时间
	$signature=$_POST['signature'];
	$cuspay=0;
	$orginbillid="";
	$sessionresult=$serverprintbill->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$cusnum.$wait.$takeout.$tabid.$food.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$serverprintbill->updateCusSession($uid, $session);
			}
			$oldfoodarr=json_decode($food,true);
			$foodarr=array();
			foreach ($oldfoodarr as $fkey=>$fval){
				$foodarr[]=array(
						"foodid"=>$fval['foodid'],
						"foodname"=>$fval['foodname'],
						"foodprice"=>$fval['foodprice'],
						"foodunit"=>$fval['foodunit'],
						"orderunit"=>$fval['orderunit'],
						"foodnum"=>$fval['foodnum'],
						"foodamount"=>$fval['foodamount'],
						"ftid"=>$fval['ftid'],
						"zoneid"=>$fval['zoneid'],
						"zonename"=>$fval['zonename'],
						"fooddisaccount"=>$fval['fooddisaccount'],
						"cooktype"=>$fval['cooktype'],
						"foodrequest"=>$fval['foodrequest'],
						"isweight"=>$fval['isweight'],
						"ishot"=>$fval['ishot'],
						"ispack"=>$fval['ispack'],
						"present"=>"0",
						"confrimweight"=>"0",//默认未确认
				);
			}
			if($paystate=="1"){
				foreach ($foodarr as $fdkey=>$fdval){
					$cuspay+=$fdval['foodprice']*$fdval['foodamount'];
				}
			}
			//赠送
			$newfoodarr=$serverprintbill->getDonateInfoByShopid($shopid, $foodarr);
			$foodarr=array_merge($foodarr,$newfoodarr);
			if(!empty($tabid)){
				$tabname=$serverprintbill->getTablenameByTabid($tabid);
			}else{
				$tabname="待定";
			}
			if($addorder=="1" && !empty($billid)){//加菜
				
			}else{//拼台或新桌
				$istabinbill=$serverprintbill->getTheTabPaid($tabid, $shopid);
				$tabstatus=$serverprintbill->getTabStatusByTabid($tabid);
				if($tabstatus=="online"){
					$orginbillid=$serverprintbill->getLastbillidByTabid($shopid, $tabid);
				}
				if($istabinbill=="1"){//桌台为开台状态加不进去
					header('Content-type: application/json');
					echo json_encode(array("billid"=>$billid, "token"=>$session));exit;
					//虚拟一个台号
// 					$newtabarr=$serverprintbill->createVirtualTab($tabid);
// 					$tabid=$newtabarr['tabid'];
// 					$tabname=$newtabarr['tabname'];
				}
			}
			
			//得到用户名
			$cusinfoarr=$serverprintbill->getCusinfo($uid,$shopid);
			$nickname=$cusinfoarr['nickname'];
			//得到商店名
			$shopinfoarr=$serverprintbill->getShopInfo($shopid);
			$shopname=$shopinfoarr['shopname'];
			$inputarr=array(
					"orderno"=>$orderno,
					"tradeno"=>$tradeno,
					"uid"=>$uid,
					"shopid"=>$shopid,
					"orginbillid"=>$orginbillid,
					"nickname"=>$nickname,
					"shopname"=>$shopname,
					"wait"=>$wait,
					"tabid"=>$tabid,
					"takeout"=>$takeout,
					"deposit"=>$deposit,
					"invoice"=>$invoice,
					"takeoutaddress"=>$takeoutaddress,
					"orderrequest"=>$orderrequest,//整单备注
					"discountype"=>$discountype,
					"paytype"=>$paytype,
					"paymoney"=>$paymoney,
					"paystatus"=>$paystatus,
					"paystate"=>$paystate,
					"tabname"=>$tabname,
					"cusnum"=>$cusnum,
					"timestamp"=>time(),//下单时间
					"billstatus"=>"done",
					"food"=>$foodarr,
			);
			$temparr=array();			
			//消费记录入库
			if($addorder=="1"&& !empty($billid)){//加菜
				$serverprintbill->addOrderToOldBill($billid, $foodarr);
				$serverprintbill->addBillRecordData($inputarr);
			}else{//第一次点菜
				//桌台状态
				if($paystate=="1"){
					$tabstatus="online";
				}else{
					$tabstatus="start";
				}
				if(!empty($tabid)){$serverprintbill->updateOneTabStatus($tabid, $tabstatus);}//桌台状态改为开台
				$billid=$serverprintbill->intoConsumeRecord($inputarr);
			}
			
			//遍历套餐
			$packagearr=json_decode($package,true);
			$packarr=$serverprintbill->getBillPackarr($billid, $packagearr);
			//更新数据库
			if(!empty($packarr)){
				$serverprintbill->updateFoodsToBill($billid, $packarr);//更新到套餐
			}
			
			if($addorder=="1"&& !empty($billid)){//加菜// && !empty($billid)
				$foodarr=array_merge($foodarr,$packarr);
				$inputarr['food']=$foodarr;
				$billarr=$inputarr;
			}else{
				$billarr=$serverprintbill->getOneBillInfoByBillid($billid);
			}
			
// 			print_r($billarr);exit;
// 			echo json_encode($billarr);exit;
			$foodRunnerArr=$serverprintbill->tobeRunner($billarr);//传菜单
			// print_r($foodRunnerArr);exit;
			$chuancaiarr=$serverprintbill->printChuanCaiData(json_encode($foodRunnerArr));//pass
			if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
			// print_r($chuancaiarr);exit;
			if(empty($paystate)){
				$cusListArr=$serverprintbill->tobeCusList($billarr);//划菜单
				$cuslistarr=$serverprintbill->printCuslistData(json_encode($cusListArr));//menu
				if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
				// print_r($cuslistarr);exit;
			}
			
			//这里是消费清单的地方
			if($paystatus=="paid" || $paystate=="1"){
				$paymethod=$paytype;
				$billarr=$serverprintbill->getOneBillInfoByBillid($billid);//得到最新的
				$billarr['food']=$foodarr;//覆盖掉
				$oldcashmoney="0";
				$oldunionmoney="0";
				$oldwechatpay="0";
				$oldvipmoney="0";
				$oldalipay="0";
				$oldmeituanpay="0";
				$olddazhongpay="0";
				$oldnuomipay="0";
				if($billarr['paystate']=="1"){//前一张单餐前买单
					$oldcashmoney=$billarr['cashmoney'];
					$oldunionmoney=$billarr['unionmoney'];
					$oldwechatpay=$billarr['wechatpay'];
					$oldvipmoney=$billarr['vipmoney'];
					$oldalipay=$billarr['alipay'];
					$oldmeituanpay=$billarr['meituanpay'];
					$olddazhongpay=$billarr['dazhongpay'];
					$oldnuomipay=$billarr['nuomipay'];
				}
				switch ($op){
					case "cashmoney":
						$cashmoney=$oldcashmoney+$cuspay;$unionmoney=$oldunionmoney;$wechatpay=$oldwechatpay;$vipmoney=$oldvipmoney;$alipay=$oldalipay;$meituanpay=$oldmeituanpay;$dazhongpay =$olddazhongpay;$nuomipay =$oldnuomipay;
						$billarr['cashmoney']=$cuspay;
						break;
					case "unionmoney":
						$cashmoney=$oldcashmoney;$unionmoney=$oldunionmoney+$cuspay;$wechatpay=$oldwechatpay;$vipmoney=$oldvipmoney;$alipay=$oldalipay;$meituanpay=$oldmeituanpay;$dazhongpay =$olddazhongpay;$nuomipay =$oldnuomipay;
						$billarr['unionmoney']=$cuspay;
						break;
					case "vipmoney":
						$cashmoney=$oldcashmoney;$unionmoney=$oldunionmoney;$wechatpay=$oldwechatpay;$vipmoney=$cuspay+$oldvipmoney;$alipay=$oldalipay;$meituanpay=$oldmeituanpay;$dazhongpay =$olddazhongpay;$nuomipay =$oldnuomipay;
						$billarr['vipmoney']=$cuspay;
						break;
					case "alipay":
						$cashmoney=$oldcashmoney;$unionmoney=$oldunionmoney;$wechatpay=$oldwechatpay;$vipmoney=$oldvipmoney;$alipay=$oldalipay+$cuspay;$meituanpay=$oldmeituanpay;$dazhongpay =$olddazhongpay;$nuomipay =$oldnuomipay;
						$billarr['alipay']=$cuspay;
						break;
					case "wechatpay":
						$cashmoney=$oldcashmoney;$unionmoney=$oldunionmoney;$wechatpay=$oldwechatpay+$cuspay;$vipmoney=$oldvipmoney;$alipay=$oldalipay;$meituanpay=$oldmeituanpay;$dazhongpay =$olddazhongpay;$nuomipay =$oldnuomipay;
						$billarr['wechatpay']=$cuspay;
						break;
					case "meituanpay":
						$cashmoney=$oldcashmoney;$unionmoney=$oldunionmoney;$wechatpay=$oldwechatpay;$vipmoney=$oldvipmoney;$alipay=$oldalipay;$meituanpay=$oldmeituanpay+$cuspay;$dazhongpay =$olddazhongpay;$nuomipay =$oldnuomipay;
						$billarr['meituanpay']=$cuspay;
						break;
					case "dazhongpay":
						$cashmoney=$oldcashmoney;$unionmoney=$oldunionmoney;$wechatpay=$oldwechatpay;$vipmoney=$oldvipmoney;$alipay=$oldalipay;$meituanpay=$oldmeituanpay;$dazhongpay =$olddazhongpay+$cuspay;$nuomipay =$oldnuomipay;
						$billarr['dazhongpay']=$cuspay;
						break;
					case "nuomipay ":
						$cashmoney=$oldcashmoney;$unionmoney=$oldunionmoney;$wechatpay=$oldwechatpay;$vipmoney=$oldvipmoney;$alipay=$oldalipay;$meituanpay=$oldmeituanpay;$dazhongpay =$olddazhongpay;$nuomipay =$oldnuomipay+$cuspay;
						$billarr['nuomipay']=$cuspay;
						break;
					default:
						$cashmoney=$oldcashmoney+$cuspay;$unionmoney=$oldunionmoney;$wechatpay=$oldwechatpay;$vipmoney=$oldvipmoney;$alipay=$oldalipay;$meituanpay=$oldmeituanpay;$dazhongpay =$olddazhongpay;$nuomipay =$oldnuomipay;
						$billarr['cashmoney']=$cuspay;
						break;
				}
				$payinputarr=array(
						"billid"=>$billid,
						"cuspay"=>$cuspay,
						"clearmoney"=>"0",
						"othermoney"=>"0",
						"discountval"=>"100",
						"cashmoney"=>$cashmoney,
						"unionmoney"=>$unionmoney,
						"vipmoney"=>$vipmoney,
						"meituanpay"=>$meituanpay,
						"alipay"=>$alipay,
						"wechatpay"=>$wechatpay,
						"dazhongpay"=>$dazhongpay,
						"nuomipay"=>$nuomipay,
						"ticketval"=>"0",
						"ticketnum"	=>"0",
						"ticketway"=>"",
						"returndepositmoney"=>"0",
						"paystate"=>$paystate,
						"paymethod"=>$paymethod,
						"cashierman"=>$nickname,
				);
				$serverprintbill->updateCommonPayData($payinputarr);
				$consumeListArr=$serverprintbill->tobeConsumeList($billarr,$paymethod,$paymoney);
				// print_r($consumeListArr);exit;//消费单，不是结账单
				$consumearr=$serverprintbill->printPrePaySheet(json_encode($consumeListArr),$op);
				if(!empty($consumearr)){$temparr[]=$consumearr;}
			}
			//厨房单
			$billarr['printerid']="";//代表按照规则打印
			$orderfoodarr=$serverprintbill->orderByprinterid($billarr);
			// print_r($orderfoodarr);exit;
			$piecelistArr=$serverprintbill->tobePieceList($orderfoodarr);
			// print_r($piecelistArr);exit;
			$kitchenarr=$serverprintbill->PrintKitchenData(json_encode($piecelistArr));
			if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
			// print_r($kitchenarr);exit;
			$urls=$serverprintbill->getUrlsArr(json_encode($temparr));
			$serverprintbill->sendFreeMessage($urls);
			header('Content-type: application/json');
			echo json_encode(array("billid"=>$billid, "token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$billid="5603c2bb5bc109d33e8b7076";
$billarr=$serverprintbill->getOneBillInfoByBillid($billid);
//厨房单
$billarr['printerid']="";//代表按照规则打印
$orderfoodarr=$serverprintbill->orderByprinterid($billarr);
// print_r($orderfoodarr);exit;
$piecelistArr=$serverprintbill->tobePieceList($orderfoodarr);
// print_r($piecelistArr);exit;
$kitchenarr=$serverprintbill->PrintKitchenData(json_encode($piecelistArr));
if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
print_r($kitchenarr);exit;
$urls=$serverprintbill->getUrlsArr(json_encode($temparr));
$serverprintbill->sendFreeMessage($urls);
exit;
$inputarr=array(
		"orderno"=>time().mt_rand(100, 999),
		"tradeno"=>"",
		"uid"=>"54769d6816c10909058b4651",
		"shopid"=>$shopid,
		"nickname"=>"lindy",
		"shopname"=>"咖啡小子",
		"wait"=>"1",
		"tabid"=>$tabid,
		"takeout"=>"1",
		"invoice"=>"杭州街坊科技有限公司",
		"takeoutaddress"=>"天目山路226号",
		"orderrequest"=>"不放辣",//整单备注
		"discountype"=>"none",
		"paytype"=>"offlinepay",
		"paystatus"=>"unpay",
		"tabname"=>$tabname,
		"cusnum"=>"5",
		"paymoney"=>"0",
		"timestamp"=>time(),//下单时间
		"billstatus"=>"done",
		"food"=>$foodarr,
);
$billarr=$serverprintbill->getOneBillInfoByBillid($billid);
// print_r($billarr);exit;

$foodRunnerArr=$serverprintbill->tobeRunner($billarr);//传菜单
// print_r($foodRunnerArr);exit;
$chuancaiarr=$serverprintbill->printChuanCaiData(json_encode($foodRunnerArr));//pass
if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
// print_r($chuancaiarr);exit;


$cusListArr=$serverprintbill->tobeCusList($billarr);//划菜单
// print_r($cusListArr);exit;
$cuslistarr=$serverprintbill->printCuslistData(json_encode($cusListArr));//menu
if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
print_r($cuslistarr);exit;

//这里是消费清单的地方
if($paystatus=="paid"){
	$paymethod=$paytype;
	$consumeListArr=$serverprintbill->tobeConsumeList($billarr,$paymethod,$paymoney);
	// print_r($consumeListArr);exit;//消费清单
	$consumearr=$serverprintbill->printConsumeListData(json_encode($consumeListArr));
	if(!empty($consumearr)){$temparr[]=$consumearr;}
}

//厨房单
$billarr['printerid']="";//代表按照规则打印
$orderfoodarr=$serverprintbill->orderByprinterid($billarr);
print_r($orderfoodarr);exit;
$piecelistArr=$serverprintbill->tobePieceList($orderfoodarr);
print_r($piecelistArr);exit;
$kitchenarr=$serverprintbill->PrintKitchenData(json_encode($piecelistArr));
if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
print_r($kitchenarr);exit;
$urls=$serverprintbill->getUrlsArr(json_encode($temparr));
// $serverprintbill->sendFreeMessage($urls);
?>
