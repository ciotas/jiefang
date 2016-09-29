<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once (_ROOT.'token/Factory/InterfaceFactory.php');
class OnlyDownSheet{
	public function getDonateInfoByShopid($shopid,$foodarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getDonateInfoByShopid($shopid, $foodarr);
	}
	public function getFoodInfoByFoodid($foodid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getFoodInfoByFoodid($foodid);
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
	public function printCuslistData($json){
		return PRINT_InterfaceFactory::createInstanceCusListWorkerDAL()->printCuslistData($json);
	}
	public function intoConsumeRecord($inputdarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->intoConsumeRecord($inputdarr);
	}
	public function tobeConsumeList($inputdarr,$paymethod,$paymoney){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr,$paymethod,$paymoney);
	}
	public function printConsumeListData($json){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json);
	}
	public function getBillPackarr($billid, $package){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillPackarr($billid, $package);
	}
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function updateCommonPayData($inputarr){
		PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateCommonPayData($inputarr);
	}
	public function orderByprinterid($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->orderByprinterid($inputdarr);
	}
	public function tobePieceList($arr){
		return PRINT_InterfaceFactory::createInstancePieceListDAL()->tobePieceList($arr);
	}
	public function printPrePaySheet($json,$op){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printPrePaySheet($json,$op);
	}
	public function updateFoodsToBill($billid, $foodarr){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateFoodsToBill($billid, $foodarr);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
	public function PrintKitchenData($json){
		return PRINT_InterfaceFactory::createInstanceKitchenWorkerDAL()->PrintKitchenData($json);
	}
	public function getBillnumToday($shopid){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->getBillnumToday($shopid);
	}
	public function addBillnumData($billid,$billnum){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addBillnumData($billid, $billnum);
	}
	public function updateSelfStock($billid){
		PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateSelfStock($billid);
	}
	public function getServeridByUid($shopid,$uid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getServeridByUid($shopid, $uid);
	}
	public function getVipdiscount($shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getVipdiscount($shopid);
	}
	public function getTheday($shopid){
		return PRINT_InterfaceFactory::createInstanceKitchenWorkerDAL()->getTheday($shopid);
	}
	public function addBillNum($shopid,$billid,$theday){
		return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->addBillNum($shopid,$billid, $theday);
	}
	public function getBillNum($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getBillNum($billid);
	}
}
$onlydownsheet=new OnlyDownSheet();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$uid=$_POST['uid'];
	$orderno="0";//订单号
	$tradeno="0";//交易号
	$tabid="";
	$wait="0";//0
	$takeout="0";//""
	$invoice="0";//""
	$orderrequest="0";//""
	$takeoutaddress="";//""
	$discountype="none";//none 无优惠
	$paystatus="paid";//付款状态，未付款unpay,已付款paid
	$paytype="serverpay";//服务员下单serverpay，顾客线上下单alipay,wechatpay ,unionpay 线下付款 offlinepay
	$payrole="server";//customer
	$cusnum=0;//根据菜品数量而定 
	$deposit="0";
	$paymoney="0";
	$food=$_POST['food'];
	$package=$_POST['package'];
	$paystate ="1";//餐前付款 0
	$op=$_POST['op'];//餐前付款方式none
	$timestamp=time();//下单时间
	$returndepositmoney="0";
	$cuspay=0;
	$orginbillid="";
	$timestamp=$_POST['timestamp'];//下单时间
	$signature=$_POST['signature'];
	$sessionresult=$onlydownsheet->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$food.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$onlydownsheet->updateCusSession($uid, $session);
			}
			//开始是实现逻辑
			$oldfoodarr=json_decode($food,true);
			$foodarr=array();
			foreach ($oldfoodarr as $fkey=>$fval){
				if(empty($fval['foodamount'])){continue;}
				$foodinfo=$onlydownsheet->getFoodInfoByFoodid($fval['foodid']);
				if(empty($foodinfo)){continue;}
				$cusnum++;
				$foodarr[]=array(
						"foodid"=>$fval['foodid'],
						"foodname"=>$foodinfo['foodname'],
						"foodprice"=>$foodinfo['foodprice'],
						"foodunit"=>$foodinfo['foodunit'],
						"orderunit"=>$foodinfo['orderunit'],
						"foodnum"=>$fval['foodnum'],
						"foodamount"=>$fval['foodamount'],
						"ftid"=>$foodinfo['foodtypeid'],
						"zoneid"=>$foodinfo['zoneid'],
						"zonename"=>$foodinfo['zonename'],
						"fooddisaccount"=>$foodinfo['fooddisaccount'],
						"cooktype"=>$fval['cooktype'],
						"foodrequest"=>"",
						"isweight"=>$foodinfo['isweight'],
						"ishot"=>$foodinfo['ishot'],
						"ispack"=>$foodinfo['ispack'],
						"present"=>"0",
						"confrimweight"=>"0",//默认未确认
				);
			}
			foreach ($foodarr as $fdkey=>$fdval){
				if(empty($fval['present'])){
					$cuspay+=$fdval['foodprice']*$fdval['foodamount'];
				}
			}
			//自动赠送
			$newfoodarr=$onlydownsheet->getDonateInfoByShopid($shopid, $foodarr);
			$foodarr=array_merge($foodarr,$newfoodarr);
			$tabname="待定";
			
			//得到用户名
			$cusinfoarr=$onlydownsheet->getCusinfo($uid,$shopid);
			$nickname=$cusinfoarr['nickname'];
			//得到商店名
			$shopinfoarr=$onlydownsheet->getShopInfo($shopid);
			$shopname=$shopinfoarr['shopname'];
			$serverid=$onlydownsheet->getServeridByUid($shopid, $uid);
			if(empty($serverid)){$serverid="boss";}
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
					"discountype"=>"none",
					"paytype"=>$paytype,
					"payrole"=>$payrole,
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
			$billid=$onlydownsheet->intoConsumeRecord($inputarr);
			
			//单子序号
			$theday=$onlydownsheet->getTheday($shopid);
			$onlydownsheet->addBillNum($shopid, $billid, $theday);
			
			//自动库存
			$onlydownsheet->updateSelfStock($billid);
			//记录点菜的人
			// 	$onlydownsheet->addUidToBill($billid, $uid);
			//遍历套餐
			$packagearr=json_decode($package,true);
			$packarr=$onlydownsheet->getBillPackarr($billid, $packagearr);
			//更新数据库
			if(!empty($packarr)){
				$onlydownsheet->updateFoodsToBill($billid, $packarr);//更新到套餐
			}
			
			$paymethod="commonpay";
			switch ($op){
				case "cashmoney": 
					$cashmoney=$cuspay;
					$unionmoney="0";
					$vipmoney="0";
					$alipay="0";
					$wechatpay="0";
					$meituanpay="0";
					$dazhongpay="0";
					$nuomipay="0";
					break;
				case "unionmoney":
					$cashmoney="0";
					$unionmoney=$cuspay;
					$vipmoney="0";
					$alipay="0";
					$wechatpay="0";
					$meituanpay="0";
					$dazhongpay="0";
					$nuomipay="0";
					break;
				case "vipmoney":
					$vipdisacount=$onlydownsheet->getVipdiscount($shopid);
					$cuspay=round($cuspay*($vipdisacount/100));
					$cashmoney="0";
					$unionmoney="0";
					$vipmoney=$cuspay;
					$alipay="0";
					$wechatpay="0";
					$meituanpay="0";
					$dazhongpay="0";
					$nuomipay="0";
					break;
				case "alipay":
					$cashmoney="0";
					$unionmoney="0";
					$vipmoney="0";
					$alipay=$cuspay;
					$wechatpay="0";
					$meituanpay="0";
					$dazhongpay="0";
					$nuomipay="0";
					break;
				case "wechatpay":
					$cashmoney="0";
					$unionmoney="0";
					$vipmoney="0";
					$alipay="0";
					$wechatpay=$cuspay;
					$meituanpay="0";
					$dazhongpay="0";
					$nuomipay="0";
					break;
				case "meituanpay":
					$cashmoney="0";
					$unionmoney="0";
					$vipmoney="0";
					$alipay="0";
					$wechatpay="0";
					$meituanpay=$cuspay;
					$dazhongpay="0";
					$nuomipay="0";
					break;
				case "dazhongpay":
					$cashmoney="0";
					$unionmoney="0";
					$vipmoney="0";
					$alipay="0";
					$wechatpay="0";
					$meituanpay="0";
					$dazhongpay=$cuspay;
					$nuomipay="0";
					break;
				case "nuomipay":
					$cashmoney="0";
					$unionmoney="0";
					$vipmoney="0";
					$alipay="0";
					$wechatpay="0";
					$meituanpay="0";
					$dazhongpay="0";
					$nuomipay=$cuspay;
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
					"otherpay"=>"0",
					"paytype"=>$paytype,
					"nuomipay"=>$nuomipay,
					"ticketval"=>"0",
					"ticketnum"	=>"0",
					"ticketway"=>"",
					"returndepositmoney"=>$returndepositmoney,
					"paystate"=>$paystate,
					"paymethod"=>$paymethod,
					"serverid"=>$serverid,
					"cashierman"=>$nickname,
			);
			$billnum=$onlydownsheet->getBillnumToday($shopid);
			$onlydownsheet->addBillnumData($billid, $billnum);
			$onlydownsheet->updateCommonPayData($payinputarr);
			$billarr=$onlydownsheet->getOneBillInfoByBillid($billid);
			
			$foodRunnerArr=$onlydownsheet->tobeRunner($billarr);//传菜单
			// print_r($foodRunnerArr);exit;
			$chuancaiarr=$onlydownsheet->printChuanCaiData(json_encode($foodRunnerArr));//pass
			if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
			
			$consumeListArr=$onlydownsheet->tobeConsumeList($billarr,$paymethod,$paymoney);
			// print_r($consumeListArr);exit;//消费单，不是结账单
			if(empty($op)){$op="cashmoney";}
			$consumearr=$onlydownsheet->printPrePaySheet(json_encode($consumeListArr),$op);
			if(!empty($consumearr)){$temparr[]=$consumearr;}
			//厨房单
			$billarr['printerid']="";//代表按照规则打印
			$billno=$onlydownsheet->getBillNum(strval($billarr['_id']));
			$billarr['billno']=$billno;
			$orderfoodarr=$onlydownsheet->orderByprinterid($billarr);
			// print_r($orderfoodarr);exit;
			$piecelistArr=$onlydownsheet->tobePieceList($orderfoodarr);
			// print_r($piecelistArr);exit;
			
			$kitchenarr=$onlydownsheet->PrintKitchenData(json_encode($piecelistArr));
			if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
			// print_r($kitchenarr);exit;
			$urls=$onlydownsheet->getUrlsArr(json_encode($temparr));
			$onlydownsheet->sendFreeMessage($urls);
			echo json_encode(array("status"=>"ok","billid"=>$billid));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$billid="566020c85bc109ab5c8b5408";
$billarr=$onlydownsheet->getOneBillInfoByBillid($billid);
$consumeListArr=$onlydownsheet->tobeConsumeList($billarr,$paymethod,$paymoney);
// print_r($consumeListArr);exit;//消费单，不是结账单
$consumearr=$onlydownsheet->printPrePaySheet(json_encode($consumeListArr),"cashmoney");
if(!empty($consumearr)){$temparr[]=$consumearr;}
print_r($consumearr);exit;
//厨房单
$billarr['printerid']="";//代表按照规则打印
$orderfoodarr=$onlydownsheet->orderByprinterid($billarr);
// print_r($orderfoodarr);exit;
$piecelistArr=$onlydownsheet->tobePieceList($orderfoodarr);
// print_r($piecelistArr);exit;
$op="cashmoney";
$kitchenarr=$onlydownsheet->printPrePaySheet(json_encode($piecelistArr),$op);
if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
print_r($kitchenarr);exit;
$urls=$onlydownsheet->getUrlsArr(json_encode($temparr));
print_r($urls);exit;
// $onlydownsheet->sendFreeMessage($urls);
?>