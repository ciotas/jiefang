<?php 
require_once ('/var/www/html/global.php');
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
require_once (_ROOT.'phwechat/Factory/BLLFactory.php');
require_once (_ROOT.'des.php');
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
	public function getWechatUserinfo($uid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getWechatUserinfo($uid);
	}
	public function payHandle($openid = '', $orderno = '', $orderfee = '', $attach = ''){
		return Wechat_BLLFactory::createInstanceWxpayBLL()->jsApiCall($openid, $orderno, $orderfee, $attach);
	}
	public function intoShop_infoData($inputarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->intoShop_infoData($inputarr);
	}
	public function intoBillShopinfo($inputarr,$tab){
		return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->intoBillShopinfo($inputarr, $tab);
	}
	public function getDIstance($inputarr){
	    return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getDIstance($inputarr);
	}
}
$preputbill=new PrePutbill();
if(isset($_REQUEST['uid'])){
	$shopid=$_REQUEST['shopid'];
	$uid=$_REQUEST['uid'];
	$food=$_REQUEST['food'];
	$cook=$_REQUEST['cook'];
	$orderno=date("YmdHis",time()).mt_rand(1000, 9999);//$_REQUEST['orderno'];//订单号
	$tradeno=$_REQUEST['tradeno'];
	$tabid=$_REQUEST['tabid'];
	$openid=$_REQUEST['openid'];
	
	$prov=$_REQUEST['prov'];
	$city=$_REQUEST['city'];
	$dist=$_REQUEST['dist'];
	$road=$_REQUEST['road'];
	$shop_name=$_REQUEST['shopname'];
	$contact=$_REQUEST['contact'];
	$phone=$_REQUEST['phone'];
	$picktime=$_REQUEST['picktime'];
	$dicountfee=isset($_REQUEST['dicountfee'])?$_REQUEST['dicountfee']:"0";
	$distributefee=isset($_REQUEST['distributefee'])?$_REQUEST['distributefee']:"0";
	$tradeno="";//交易号
	$wait="0";//0
	$takeout="0";//
	$invoice="";//
	$orderrequest=$_REQUEST['orderrequest'];//""
	$takeoutaddress="";//""
	$discountype="none";//none 无优惠
	$paystatus="unpay";//付款状态，未付款unpay,已付款paid
	$paytype="";//服务员下单serverpay，顾客线上下单alipay,wechatpay ,unionpay 线下付款 offlinepay
	$payrole="customer";//customer
	$cusnum="2";
	$deposit="0";
	$paymoney="0";
	$paystate ="0";//餐前付款 0
	$op="none";//餐前付款方式none
	$type="inhouse";//inhouse takeout
// 	$beforebillid=$_REQUEST['billid'];
	$timestamp=time();//下单时间
	$cuspay=0;
	$orginbillid="";
	$addflag=false;
	//开始是实现逻辑
	$oldorgfoodarr=array();
	$foodarr=array();
	$oldfoodarr=json_decode($food,true);
	$cookarr=json_decode($cook,true);
	foreach ($oldfoodarr as $key=>$val){
	    foreach ($val as $fkey=>$fval){
	        $oldorgfoodarr[]=$fval;
	    }
	}
	foreach ($oldorgfoodarr as $fkey=>$fval){
        if(empty($fval['foodNum'])){continue;}
        $foodinfo=$preputbill->getFoodInfoByFoodid($fval['foodId']);
        if(empty($foodinfo)){continue;}
        $cooktype="";
        foreach ($cookarr as $ckey=>$cval){
        	if($cval[$fval['foodId']]['id']==$fval['foodId']){
        		$cooktype=implode("，", $cval[$fval['foodId']]['checked']);break;
        	}
        }
        $paymoney+=$fval['foodNum']*$foodinfo['foodprice'];
        $foodarr[]=array(
            "foodid"=>$fval['foodId'],
            "foodname"=>$foodinfo['foodname'],
            "foodprice"=>$foodinfo['foodprice'],
            "foodunit"=>$foodinfo['foodunit'],
            "orderunit"=>$foodinfo['foodunit'],
            "foodnum"=>$fval['foodNum'],
            "foodamount"=>$fval['foodNum'],
            "ftid"=>$foodinfo['foodtypeid'],
            "zoneid"=>$foodinfo['zoneid'],
            "zonename"=>$foodinfo['zonename'],
            "fooddisaccount"=>$foodinfo['fooddisaccount'],
            "cooktype"=>$cooktype,
            "foodrequest"=>"",
            "isweight"=>$foodinfo['isweight'],
            "ishot"=>$foodinfo['ishot'],
            "ispack"=>$foodinfo['ispack'],
            "present"=>"0",
            "confrimweight"=>"0",//默认未确认
        );
	}
	$paymoney=$paymoney-$dicountfee+$distributefee;
	if(!empty($dicountfee)){
	    $dicountfeearr=array(
	        "foodid"=>"",
	        "foodname"=>"外卖优惠",
	        "foodprice"=>$dicountfee,
	        "foodunit"=>"",
	        "orderunit"=>"",
	        "foodnum"=>"1",
	        "foodamount"=>"1",
	        "ftid"=>"",
	        "zoneid"=>"",
	        "zonename"=>"",
	        "fooddisaccount"=>"0",
	        "cooktype"=>"",
	        "foodrequest"=>"",
	        "isweight"=>"0",
	        "ishot"=>"0",
	        "ispack"=>"0",
	        "present"=>"0",
	        "confrimweight"=>"0",//默认未确认
	    );
	    $foodarr[]=$dicountfeearr;
	}
	if(!empty($distributefee)){
	    $distributefeearr=array(
	        "foodid"=>"",
	        "foodname"=>"配送费",
	        "foodprice"=>$distributefee,
	        "foodunit"=>"",
	        "orderunit"=>"",
	        "foodnum"=>"1",
	        "foodamount"=>"1",
	        "ftid"=>"",
	        "zoneid"=>"",
	        "zonename"=>"",
	        "fooddisaccount"=>"0",
	        "cooktype"=>"",
	        "foodrequest"=>"",
	        "isweight"=>"0",
	        "ishot"=>"0",
	        "ispack"=>"0",
	        "present"=>"0",
	        "confrimweight"=>"0",//默认未确认
	    );
	    $foodarr[]=$distributefeearr;
	}
	
// 	file_put_contents("/var/www/html/log.txt",json_encode($foodarr));
	//自动赠送
	$newfoodarr=$preputbill->getDonateInfoByShopid($shopid, $foodarr);
	$foodarr=array_merge($foodarr,$newfoodarr);
	//遍历套餐
	$packarr=$preputbill->getPacksData($shopid, $foodarr);
	
	//判断是否加菜
	$billinfo=array();
	
	$tabname="";
	$tabstatus="empty";

	//得到用户名
	$cusinfoarr=$preputbill->getWechatUserinfo($uid);
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
	
	$beforebillid=$preputbill->intoBeforebillConsumeRecord($inputarr);
	//录入商家信息
	$barr=array(
			"prov"=>$prov,
			"city"=>$city,
			"dist"=>$dist,
			"road"=>$road,
			"shopname"=>$shop_name,
			"contact"=>$contact,
			"phone"=>$phone,
			"orderrequest"=>$orderrequest,
			"picktime"=>$picktime,
	);
	$barr['shopid']=$shopid;
	$barr['uid']=$uid;
	$preputbill->intoShop_infoData($barr);
	//
	unset($barr['shopid']);
	unset($barr['uid']);
	$barr['billid']=$beforebillid;
	
	$preputbill->intoBillShopinfo($barr, "prebillshopinfo");
	
	if(!empty($packarr)){
// 		$preputbill->updateFoodsToBeforeBill($beforebillid, $packarr);//更新到套餐
	}
    $disarr=array(
        "shopid"=>$shopid,
        "prov"=>$prov,
        "city"=>$city,
        "dist"=>$dist,
        "road"=>$road,
    );
//    $dis= $preputbill->getDIstance($disarr);
    $dis=0;
    if(empty($paymoney)){
        $paymoney="0.01";
    }
     $jsApiParametersarr=array();
     if(!empty($openid)){
     	$attacharr=array(
     			'openid'=>$openid, 
     			'billid'=>$beforebillid, 
     			'orderfee'=>$paymoney,
     	);
     	$attach = json_encode($attacharr);
     	$result = $preputbill->payHandle($openid, $orderno, $paymoney, $attach);
     	$jsApiParametersarr=json_decode($result['jsApiParameters'],true);
     }
	
	$arr=array(
			"status"=>"ok",
			"billid"=>$beforebillid,
			"orderno"=>$orderno,
			"shopid"=>$shopid,
			"uid"=>$uid,
			"tabid"=>$tabid,
			"paymoney"=>$paymoney,
			"orderrequest"=>$orderrequest,
			"jsApiParameters"=>$jsApiParametersarr,
	        "dis"=>$dis,
	);
	echo json_encode($arr);
	
}

?>