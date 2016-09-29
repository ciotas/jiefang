<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
//require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class CusPrintBill{
	public function getDonateInfoByShopid($shopid,$foodarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getDonateInfoByShopid($shopid, $foodarr);
	}
	public function getTablenameByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTablenameByTabid($tabid);
	}
	public function getBillidByTabid($shopid,$tabid,$token){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillidByTabid($shopid, $tabid,$token);
	}
	public function getTabStatusByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTabStatusByTabid($tabid);
	}
	public function getLastbillidByTabid($shopid,$tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getLastbillidByTabid($shopid, $tabid);
	}
	public function getCusinfo($uid,$shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getCusInfo($uid,$shopid);
	}
	public function getShopInfo($shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getShopInfo($shopid);
	}
	public function addOrderToOldBill($oldbillid, $foodarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addOrderToOldBill($oldbillid, $foodarr);
	}
	public function addBillRecordData($inputarr){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addBillRecordData($inputarr);
	}
	public function updateOneTabStatus($tabid,$tabstatus){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateOneTabStatus($tabid, $tabstatus);
	}
	public function intoConsumeRecord($inputdarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->intoConsumeRecord($inputdarr);
	}
	public function getBillPackarr($billid, $package){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillPackarr($billid, $package);
	}
	public function updateFoodsToBill($billid, $foodarr){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateFoodsToBill($billid, $foodarr);
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
	public function getPacksData($shopid,$foodarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getPacksData($shopid, $foodarr);
	}
	public function addUidToBill($billid,$uid){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addUidToBill($billid, $uid);
	}
	public function getFoodInfoByFoodid($foodid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getFoodInfoByFoodid($foodid);
	}
}
$cusprintbill=new CusPrintBill();
if(isset($_REQUEST['uid'])){
	$shopid=$_REQUEST['shopid'];
	$uid=$_REQUEST['uid'];
	$orderno=$_REQUEST['orderno'];//订单号
	$tradeno=$_REQUEST['tradeno'];//交易号
	$tabid=$_REQUEST['tabid'];
	$wait=$_REQUEST['wait'];//0
// 	$billid=$_REQUEST['billid'];//加菜时不为空
	$takeout=$_REQUEST['takeout'];//""
	$invoice=$_REQUEST['invoice'];//""
	$orderrequest=$_REQUEST['orderrequest'];//""
	$takeoutaddress=$_REQUEST['takeoutaddress'];//""
	$discountype=$_REQUEST['discountype'];//none 无优惠
	$paystatus=$_REQUEST['paystatus'];//付款状态，未付款unpay,已付款paid
	$paytype=$_REQUEST['paytype'];//服务员下单serverpay，顾客线上下单alipay,wechatpay ,unionpay 线下付款 offlinepay
	$payrole=$_REQUEST['payrole'];//customer
	$cusnum=$_REQUEST['cusnum'];
	$deposit="0";
	$paymoney="0";
	$food=$_REQUEST['food'];
// 	$package=$_REQUEST['package'];
	$paystate =$_REQUEST['paystate'];//餐前付款 0
	$op="none";//餐前付款方式none
	$timestamp=time();//下单时间
	$cuspay=0;
	$orginbillid="";
	//开始是实现逻辑
	$oldfoodarr=json_decode($food,true);
	$foodarr=array();
	foreach ($oldfoodarr as $fkey=>$fval){
		if(empty($fval['foodamount'])){continue;}
		$foodinfo=$cusprintbill->getFoodInfoByFoodid($fval['foodid']);
		if(empty($foodinfo)){continue;}
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
	if(empty($foodarr)){return ;}
	//自动赠送
	$newfoodarr=$cusprintbill->getDonateInfoByShopid($shopid, $foodarr);
	if(!empty($newfoodarr)){
		$foodarr=array_merge($foodarr,$newfoodarr);
	}
	if(!empty($tabid)){
		$tabname=$cusprintbill->getTablenameByTabid($tabid);
	}else{
		$tabname="未知";
	}
	//根据桌台判断是否加菜
	$prebillinfo=$cusprintbill->getBillidByTabid($shopid, $tabid, "");
	if($prebillinfo['paystatus']=="paid"){
	//	echo json_encode(array("status"=>"notpermission"));exit;
	}
	//必点菜
	if(empty($prebillinfo['billid'])){
		$mustfoodarr=$cusprintbill->getMustOrderMenuData($shopid, $cusnum);
		$foodarr=array_merge($foodarr,$mustfoodarr);
	}
	//得到用户名
	$cusinfoarr=$cusprintbill->getCusinfo($uid,$shopid);
	$nickname=$cusinfoarr['nickname'];
	//得到商店名
	$shopinfoarr=$cusprintbill->getShopInfo($shopid);
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
	file_put_contents("/var/www/html/printbill/log/wechat.log",time(),FILE_APPEND);
	file_put_contents("/var/www/html/printbill/log/wechat.log","\r\n",FILE_APPEND);
	file_put_contents("/var/www/html/printbill/log/wechat.log", json_encode($inputarr),FILE_APPEND);
	$temparr=array();
	if(!empty($prebillinfo['billid'])){//加菜
		$cusprintbill->addOrderToOldBill($prebillinfo['billid'], $foodarr);
		$cusprintbill->addBillRecordData($inputarr);
		$billid=$prebillinfo['billid'];
	}else{
		$tabstatus="start";
		if(!empty($tabid)){$cusprintbill->updateOneTabStatus($tabid, $tabstatus);}//桌台状态改为开台
		$billid=$cusprintbill->intoConsumeRecord($inputarr);
	}
	//记录点菜的人
	$cusprintbill->addUidToBill($billid, $uid);
	//遍历套餐
// 	$packagearr=json_decode($package,true);
// 	$packarr=$cusprintbill->getBillPackarr($billid, $packagearr);
	$packarr=$cusprintbill->getPacksData($shopid, $foodarr);
	//更新数据库
	if(!empty($packarr)){
		$cusprintbill->updateFoodsToBill($billid, $packarr);//更新到套餐
	}
	if(!empty($prebillinfo['billid'])){//加菜
		$foodarr=array_merge($foodarr,$packarr);
		$inputarr['food']=$foodarr;
		$billarr=$inputarr;
	}else{
		$billarr=$cusprintbill->getOneBillInfoByBillid($billid);
	}
	
	$foodRunnerArr=$cusprintbill->tobeRunner($billarr);//传菜单
	// print_r($foodRunnerArr);exit;
	$chuancaiarr=$cusprintbill->printChuanCaiData(json_encode($foodRunnerArr));//pass
	if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
	// print_r($chuancaiarr);exit;
	
	$cusListArr=$cusprintbill->tobeCusList($billarr);//划菜单
	$cuslistarr=$cusprintbill->printCuslistData(json_encode($cusListArr));//menu
	if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
	//厨房单
	$billarr['printerid']="";//代表按照规则打印
	$orderfoodarr=$cusprintbill->orderByprinterid($billarr);
	// print_r($orderfoodarr);exit;
	$piecelistArr=$cusprintbill->tobePieceList($orderfoodarr);
	// print_r($piecelistArr);exit;
	$kitchenarr=$cusprintbill->PrintKitchenData(json_encode($piecelistArr));
	if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
	// print_r($kitchenarr);exit;
	$urls=$cusprintbill->getUrlsArr(json_encode($temparr));
	$cusprintbill->sendFreeMessage($urls);
	echo json_encode(array("status"=>"ok","billid"=>$billid));
}
exit;
//test
// $billid="55f50e217cc1095e108b4567";
// $uid="554ad8cc5bc109d7518b45b6";
$shopid="554ad9615bc109d8518b45d2";
$tabid="55c05d985bc109f13e8b4c88";//55c05d985bc109f13e8b4c88
// $cusprintbill->addUidToBill($billid, $uid);
$prebillinfo=$cusprintbill->getBillidByTabid($shopid, $tabid, "");
print_r($prebillinfo);exit;
exit;
$shopid="554ad9615bc109d8518b45d2";
$foodarr=array();
$foodstr='{"55bb519e5bc109f13e8b4b85":{"foodid":"55bb519e5bc109f13e8b4b85","cooktype":"","isweight":"0","ispack":"1","foodname":"108套餐","foodcooktype":"","foodprice":"107","foodnum":6,"foodamount":6},"554c8d785bc109d8518b45eb":{"foodid":"554c8d785bc109d8518b45eb","cooktype":"","isweight":"0","ispack":"0","foodname":"土豆","foodcooktype":"炒","foodprice":"15","foodnum":5,"foodamount":5},"554afa365bc1092b7a8b4578":{"foodid":"554afa365bc1092b7a8b4578","cooktype":"","isweight":"0","ispack":"0","foodname":"96套餐","foodcooktype":"啊啊","foodprice":"96","foodnum":9,"foodamount":9},"554b0c6b5bc109d8518b45e4":{"foodid":"554b0c6b5bc109d8518b45e4","cooktype":"","isweight":"0","ispack":"0","foodname":"星巴克白牡丹茶","foodcooktype":"","foodprice":"20","foodnum":4,"foodamount":4},"554b05595bc109dd518b45c3":{"foodid":"554b05595bc109dd518b45c3","cooktype":"炸","isweight":"0","ispack":"0","foodname":"鸡蛋","foodcooktype":"煮、炸、蒸","foodprice":"6","foodnum":1,"foodamount":1}}';
$foodarr=json_decode($foodstr,true);
$packarr=$cusprintbill->getPacksData($shopid, $foodarr);
print_r($packarr);exit;
$uid="554ad8cc5bc109d7518b45b5";

$orderno="1234567890";//订单号
$tradeno="";//交易号
$tabid="5565d1015bc1092b7a8b9687";//A108
$wait="0";//0
$takeout="";//""
$invoice="";//""
$orderrequest="";//""
$takeoutaddress="";//""
// 	$discountype=$_REQUEST['discountype'];//none 无优惠
$paystatus="unpay";//付款状态，未付款unpay,已付款paid
$paytype="offlinepay";//服务员下单serverpay，顾客线上下单alipay,wechatpay ,unionpay 线下付款 offlinepay
$payrole="customer";
$cusnum="4";
$deposit="0";
$paymoney="0";
$food='[ { "foodid" : "554b0c6b5bc109d8518b45e5", "foodname" : "英式红茶", "foodprice" : "20", "foodunit" : "杯", "orderunit" : "杯", "foodnum" : "1", "foodamount" : "1", "ftid" : "554ae2295bc1092b7a8b4576", "zoneid" : "554adab55bc1092b7a8b4573", "zonename" : "酒水", "fooddisaccount" : "1", "cooktype" : "", "foodrequest" : "", "isweight" : "0", "ishot" : "0", "ispack" : "0", "present" : "0", "confrimweight" : "0" }, { "foodid" : "554b0c6b5bc109d8518b45e5", "foodname" : "英式红茶", "foodprice" : "20", "foodunit" : "杯", "orderunit" : "杯", "foodnum" : "1", "foodamount" : "1", "ftid" : "554ae2295bc1092b7a8b4576", "zoneid" : "554adab55bc1092b7a8b4573", "zonename" : "酒水", "fooddisaccount" : "1", "cooktype" : "", "foodrequest" : "", "isweight" : "0", "ishot" : "0", "ispack" : "0", "present" : "0", "confrimweight" : "0" }, { "foodid" : "554b0c6b5bc109d8518b45e1", "foodname" : "特浓巧克力布朗尼", "foodprice" : "20", "foodunit" : "例", "orderunit" : "例", "foodnum" : "1", "foodamount" : "1", "ftid" : "554ae3185bc109d5518b45da", "zoneid" : "554adaad5bc1092b7a8b4572", "zonename" : "点心", "fooddisaccount" : "1", "cooktype" : "", "foodrequest" : "", "isweight" : "0", "ishot" : "0", "ispack" : "0", "present" : "0", "confrimweight" : "0" } ]';

$package='[{"foodid":"5474872f16c1090c058b462f","pack":["552b4b895bc109dc548b457e","552b3f8e5bc109dd408b4572"]},{"foodid":"5474872f16c1090c058b462f","pack":["552b4b895bc109dc548b457e","552b3f8e5bc109dd408b4572"]}]';;
$paystate ="0";//餐前付款 0
$op="none";//餐前付款方式none
$timestamp=time();//下单时间
$cuspay=0;
$orginbillid="";
//开始是实现逻辑
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
//自动赠送
$newfoodarr=$cusprintbill->getDonateInfoByShopid($shopid, $foodarr);
// print_r($newfoodarr);exit;
$foodarr=array_merge($foodarr,$newfoodarr);
if(!empty($tabid)){
	$tabname=$cusprintbill->getTablenameByTabid($tabid);
}else{
	$tabname="待定";
}
// echo $tabname;exit;
//根据桌台判断是否加菜
$prebillinfo=$cusprintbill->getBillidByTabid($shopid, $tabid, "");
// print_r($prebillinfo);exit;
if($prebillinfo['paystatus']=="paid"){
	echo json_encode(array("status"=>"notpermission"));exit;
}
//必点菜
// print_r($prebillinfo);exit;
if(empty($prebillinfo['billid'])){
	$mustfoodarr=$cusprintbill->getMustOrderMenuData($shopid, $cusnum);
	$foodarr=array_merge($foodarr,$mustfoodarr);
}
// print_r($foodarr);exit;
//得到用户名
$cusinfoarr=$cusprintbill->getCusinfo($uid,$shopid);
$nickname=$cusinfoarr['nickname'];
//得到商店名
$shopinfoarr=$cusprintbill->getShopInfo($shopid);
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
// print_r($inputarr);exit;
$temparr=array();
// if(!empty($prebillinfo['billid'])){//加菜
// 	$cusprintbill->addOrderToOldBill($prebillinfo['billid'], $foodarr);
// 	$cusprintbill->addBillRecordData($inputarr);
// 	$billid=$prebillinfo['billid'];
// }else{
// 	$tabstatus="start";
// 	if(!empty($tabid)){$cusprintbill->updateOneTabStatus($tabid, $tabstatus);}//桌台状态改为开台
// 	$billid=$cusprintbill->intoConsumeRecord($inputarr);
// }
$billid="55f50e217cc1095e108b4567";
//遍历套餐
$packagearr=json_decode($package,true);
$packarr=$cusprintbill->getBillPackarr($billid, $packagearr);
// print_r($packarr);exit;
//更新数据库
if(!empty($packarr)){
	$cusprintbill->updateFoodsToBill($billid, $packarr);//更新到套餐
}
if(!empty($prebillinfo['billid'])){//加菜
	$foodarr=array_merge($foodarr,$packarr);
	$inputarr['food']=$foodarr;
	$billarr=$inputarr;
}else{
	$billarr=$cusprintbill->getOneBillInfoByBillid($billid);
}
// print_r($billarr);exit;
$foodRunnerArr=$cusprintbill->tobeRunner($billarr);//传菜单
// print_r($foodRunnerArr);exit;
$chuancaiarr=$cusprintbill->printChuanCaiData(json_encode($foodRunnerArr));//pass
if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
// print_r($chuancaiarr);exit;

$cusListArr=$cusprintbill->tobeCusList($billarr);//划菜单
$cuslistarr=$cusprintbill->printCuslistData(json_encode($cusListArr));//menu
if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
//厨房单
$billarr['printerid']="";//代表按照规则打印
$orderfoodarr=$cusprintbill->orderByprinterid($billarr);
// print_r($orderfoodarr);exit;
$piecelistArr=$cusprintbill->tobePieceList($orderfoodarr);
// print_r($piecelistArr);exit;
$kitchenarr=$cusprintbill->PrintKitchenData(json_encode($piecelistArr));
if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
// print_r($kitchenarr);exit;
$urls=$cusprintbill->getUrlsArr(json_encode($temparr));
$cusprintbill->sendFreeMessage($urls);
?>
