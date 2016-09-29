<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class PrePutbill{
	public function getDonateInfoByShopid($shopid,$foodarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getDonateInfoByShopid($shopid, $foodarr);
	}
	public function getCusinfo($uid,$shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getCusInfo($uid,$shopid);
	}
	public function getShopInfo($shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getShopInfo($shopid);
	}
	public function addOrderToOldBeforeBill($oldbillid, $foodarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addOrderToOldBeforeBill($oldbillid, $foodarr);
	}
	public function intoBeforebillConsumeRecord($inputdarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->intoBeforebillConsumeRecord($inputdarr);
	}
	public function getBillPackarr($billid, $package){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillPackarr($billid, $package);
	}
	public function updateFoodsToBeforeBill($billid, $foodarr){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateFoodsToBeforeBill($billid, $foodarr);
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
	public function getBeforeBillData($shopid,$uid,$type){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBeforeBillData($shopid, $uid,$type);
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
	public function getOneBillInfoByBeforeBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBeforeBillid($billid);
	}
	public function getBillAndPayStatus($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillAndPayStatus($billid);
	}
	public function printCuslistData($json){
		return PRINT_InterfaceFactory::createInstanceCusListWorkerDAL()->printCuslistData($json);
	}
	public function getTabidByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTabidByBillid($billid);	
	}
	public function getTablenameByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTablenameByTabid($tabid);
	}
	public function getTabStatusByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTabStatusByTabid($tabid);
	}
}
$preputbill=new PrePutbill();

if(isset($_REQUEST['uid'])){
	$shopid=$_REQUEST['shopid'];
	$uid=$_REQUEST['uid'];
	$orderno=$_REQUEST['orderno'];//订单号
	$tradeno=$_REQUEST['tradeno'];//交易号
// 	$tabid=$_REQUEST['tabid'];
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
	$paystate =$_REQUEST['paystate'];//餐前付款 0
	$op="none";//餐前付款方式none
	$type=$_REQUEST['type'];//inhouse takeout
	$beforebillid=$_REQUEST['billid'];
	$timestamp=time();//下单时间
	$cuspay=0;
	$orginbillid="";
	$addflag=false;
	//开始是实现逻辑
	$oldfoodarr=json_decode($food,true);
	$foodarr=array();
	foreach ($oldfoodarr as $fkey=>$fval){
		if(empty($fval['foodamount'])){continue;}
		$foodinfo=$preputbill->getFoodInfoByFoodid($fval['foodid']);
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
	//自动赠送
	$newfoodarr=$preputbill->getDonateInfoByShopid($shopid, $foodarr);
	$foodarr=array_merge($foodarr,$newfoodarr);
	//遍历套餐
	$packarr=$preputbill->getPacksData($shopid, $foodarr);
	
	//判断是否加菜
	$billinfo=array();
	if(!empty($beforebillid)){
		$addflag=true;
	}else{
		$beforebillinfo=$preputbill->getBeforeBillData($shopid, $uid,$type);
		$beforebillid="";
		if(!empty($beforebillinfo)){
			$beforebillid=$beforebillinfo['beforebillid'];
			$addflag=true;
		}
	}
	$tabname="";
	$tabstatus="empty";
// 	if($addflag && $takeout=="0"){
// 		$tabid=$preputbill->getTabidByBillid($billid);
// 		$tabname=$preputbill->getTablenameByTabid($tabid);
// 		//桌台状态
// 		$tabstatus=$preputbill->getTabStatusByTabid($tabid);
// 	}

	//得到用户名
	$cusinfoarr=$preputbill->getCusinfo($uid,$shopid);
	$nickname=$cusinfoarr['nickname'];
	//得到商店名
	$shopinfoarr=$preputbill->getShopInfo($shopid);
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
			"tabid"=>"",
			"tabname"=>$tabname,
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
			"cusnum"=>$cusnum,
			"timestamp"=>time(),//下单时间
			"billstatus"=>"undone",
			"food"=>$foodarr,
	);
	
	if($addflag){
		$preputbill->addOrderToOldBeforeBill($beforebillid, $foodarr);
	}else{
		$beforebillid=$preputbill->intoBeforebillConsumeRecord($inputarr);
	}
	if(!empty($packarr)){
		$preputbill->updateFoodsToBeforeBill($beforebillid, $packarr);//更新到套餐
	}
	echo json_encode(array("status"=>"ok","billid"=>$beforebillid));
}

exit;
$uid="562070ff5bc10934758b4645";
$shopid="554ad9615bc109d8518b45d2";
$orderno="1446812913880";
$tradeno="";
$wait="0";
$type="tab";
$billid="";
$takeout="0";
$orderrequest="";
$invoice="";
$takeoutaddress="";
$discountype="none";
$paytype="offlinepay";
$payrole="customer";
$paystatus="unpay";
$billstatus="undone";
$cusnum="1";
$paymoney="0";
$oldfoodarr =array(
		"0"=>array(
				"foodid"=>"554c8d785bc109d8518b45eb",
				"cooktype"=>"",
				"isweight"=>"0",
				"ispack"=>"0",
				"foodname"=>"aaa",
				"foodcooktype"=>"",
				"foodprice"=>"20",
				"foodnum"=>"1",
				"foodamount"=>"1",
		),
);

$foodarr=array();
foreach ($oldfoodarr as $fkey=>$fval){
	if(empty($fval['foodamount'])){continue;}
	$foodinfo=$preputbill->getFoodInfoByFoodid($fval['foodid']);
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
//自动赠送
$newfoodarr=$preputbill->getDonateInfoByShopid($shopid, $foodarr);
$foodarr=array_merge($foodarr,$newfoodarr);
//遍历套餐
$packarr=$preputbill->getPacksData($shopid, $foodarr);

//更新数据库

//判断是否加菜

$billinfo=array();
if(!empty($billid)){
	$addflag=true;
}else{
	$billinfo=$preputbill->getBillData($shopid, $uid,$type);
	if(!empty($billinfo)){
		$addflag=true;
		$billid=$billinfo['billid'];
	}
}
$tabname="";
$tabstatus="empty";
if($addflag && $takeout=="0"){
	$tabid=$preputbill->getTabidByBillid($billid);
	$tabname=$preputbill->getTablenameByTabid($tabid);
	//桌台状态
	$tabstatus=$preputbill->getTabStatusByTabid($tabid);
}

//得到用户名
$cusinfoarr=$preputbill->getCusinfo($uid,$shopid);
$nickname=$cusinfoarr['nickname'];
//得到商店名
$shopinfoarr=$preputbill->getShopInfo($shopid);
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
		"tabname"=>$tabname,
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
		"cusnum"=>$cusnum,
		"timestamp"=>time(),//下单时间
		"billstatus"=>"undone",
		"food"=>$foodarr,
);
$billinfo2=array();
if(!empty($billid)){
	$billinfo2=$preputbill->getOneBillInfoByBillid($billid);
}
if( ($billinfo2['billstatus']=="undone" && $addflag) || ($billinfo2['billstatus']=="done" &&  ($tabstatus=="start" || $tabstatus=="online") ) ){
	// 	if($addflag && ($tabstatus=="start" || $tabstatus=="online") && $type=="inhouse" || ($type=="inhouse" || $type=="tab" || $type=="takeout"  && $addflag)){//加菜
	if(!empty($packarr)){
		$preputbill->updateFoodsToBill($billid, $packarr);//更新到套餐
	}
	$preputbill->addOrderToOldBill($billid, $foodarr);
	$billinfo1=$preputbill->getBillAndPayStatus($billid);
	$billstatus=$billinfo1['billstatus'];
}else{
	// 		$tabstatus="start";
	// 		if(!empty($tabid)){$cusprintbill->updateOneTabStatus($tabid, $tabstatus);}//桌台状态改为开台
	$billstatus="undone";
	$billid=$preputbill->intoConsumeRecord($inputarr);
}
//记录点菜的人
$billarr=array();//要打印的单子
$preputbill->addUidToBill($billid, $uid);
if($addflag && ($tabstatus=="start" || $tabstatus=="online")){//加
	$foodarr=array_merge($foodarr,$packarr);
	$inputarr['food']=$foodarr;
	$billarr=$inputarr;
}
if(!empty($billarr) && $billstatus=="done"){//加菜的打印场景
	$foodRunnerArr=$preputbill->tobeRunner($billarr);//传菜单
	// print_r($foodRunnerArr);exit;
	$chuancaiarr=$preputbill->printChuanCaiData(json_encode($foodRunnerArr));//pass
	if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
	// print_r($chuancaiarr);exit;
	
	$cusListArr=$preputbill->tobeCusList($billarr);//划菜单
	$cuslistarr=$preputbill->printCuslistData(json_encode($cusListArr));//menu
	if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
	//厨房单
	$billarr['printerid']="";//代表按照规则打印
	$orderfoodarr=$preputbill->orderByprinterid($billarr);
	// print_r($orderfoodarr);exit;
	$piecelistArr=$preputbill->tobePieceList($orderfoodarr);
	// print_r($piecelistArr);exit;
	$kitchenarr=$preputbill->PrintKitchenData(json_encode($piecelistArr));
	if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
	// print_r($kitchenarr);exit;
	$urls=$preputbill->getUrlsArr(json_encode($temparr));
	$preputbill->sendFreeMessage($urls);
}
echo json_encode(array("status"=>"ok","billid"=>$billid));
?>