<?php
require_once ('/var/www/html/global.php');
require_once (_ROOT.'phwechat/Factory/BLLFactory.php');
require_once (_ROOT.'houtai/shop/Factory/InterfaceFactory.php');
require_once (_ROOT.'printbill/Factory/InterfaceFactory.php');
class NotifyurlClass{
    public function notifyUrl($code = ''){
        $this->write_logs($code);
        return Wechat_BLLFactory::createInstanceWxpayBLL()->notifyUrl(); 
        $this->write_logs('=======END==================');
    }
    public function getPreBillByBillid($billid){
    	return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getPreBillByBillid($billid);
    }
    public function updateCommonPayData($inputarr){
    	return PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateCommonPayData($inputarr);
    }
    public function getOneBillInfoByBillid($billid){
    	return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
    }
    public function getOneBillInfoByBeforeBillid($oldbeforebillid){
    	return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBeforeBillid($oldbeforebillid);
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
    public function getFoodInfoByFoodid($foodid){
    	return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getFoodInfoByFoodid($foodid);
    }
    public function intoConsumeRecord($inputdarr){
    	return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->intoConsumeRecord($inputdarr);
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
    public function emoji2str($str){
    	return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->emoji2str($str);
    }
    public function getTablenameByTabid($tabid){
    	return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTablenameByTabid($tabid);
    }
    public function getOneBillShopinfo($tab,$billid){
    	return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getOneBillShopinfo($tab, $billid);
    }
    public function intoBillShopinfo($inputarr,$tab){
    	return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->intoBillShopinfo($inputarr, $tab);
    }
    public function intoBillInnerShopinfo($inputarr,$tab){
        return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->intoBillInnerShopinfo($inputarr, $tab);
    }
    public function getTheday($shopid){
        return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getTheday($shopid);
    }
    public function addBillNum($shopid,$billid,$theday){
        return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->addBillNum($shopid,$billid, $theday);
    }
    public function getBillNum($billid){
        return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->getBillNum($billid);
    }
    public function updateSelfStock($billid){
        PRINT_InterfaceFactory::createInstancePayMoneyDAL()->updateSelfStock($billid);
    }
    public function sendSMSBillInfo($billid,$op){
        QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->sendSMSBillInfo($billid,$op);
    }
    public function isReDownbill($shopid,$uid,$foodjson){
        return PRINT_InterfaceFactory::createInstanceDoWorkerTwoDAL()->isReDownbill($shopid,$uid,$foodjson);
    }
    public function addBalance($shopid,$paymoney){
        PRINT_InterfaceFactory::createInstancePayMoneyDAL()->addBalance($shopid,$paymoney);
    }
    public function write_logs($content = '') {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $path = '/data/www/logs/'.$date.'.log';
        $str = $time.': '.$content.PHP_EOL;
        file_put_contents($path, $str, FILE_APPEND);
    }
}
$notifyurl = new NotifyurlClass();
$code="";
$data = $notifyurl->notifyUrl($code);
if(!empty($data)){
	$billid=$data['billid'];
	$type=$data['type'];
	$orderfee=$data['orderfee'];
	$beforeinfo=$notifyurl->getOneBillInfoByBeforeBillid($billid);
	$orderno= $beforeinfo["orderno"];
	$tabid=$beforeinfo['tabid'];
	$uid=$beforeinfo['uid'];
	$shopid=$beforeinfo['shopid'];
	$orderrequest=$beforeinfo['orderrequest'];
	
	$tabname=$notifyurl->getTablenameByTabid($tabid);
	$paytype="wechatpay";
	$shopname=$beforeinfo['shopname'];
	$foodarr=$beforeinfo['food'];
	$nickname=$beforeinfo['nickname'];//处理emoji表情
	$nickname=$notifyurl->emoji2str($nickname);
	$cusnum=isset($beforeinfo['cusnum'])?$beforeinfo['cusnum']:2;
	$clearmoney="0";
	$ticketval="0";
	$ticketnum="0";
	$ticketway="";
	$discountval="100";
	$discountmode="part";
	$returndepositmoney="0";
	$paymethod=$paytype;
	$paymoney=$orderfee;
	$billexist=$notifyurl->isReDownbill($shopid,$uid,json_encode($foodarr));
	if($billexist){return ;}
	$inputarr=array(
			"tradeno"=>"",
			"orderno"=>$orderno,
			"uid"=>$uid,
			"shopid"=>$shopid,
			"nickname"=>$nickname,
			"shopname"=>$shopname,
			"wait"=>$beforeinfo['wait'],
			"tabid"=>$tabid,
			"takeout"=>$beforeinfo['takeout'],
			"invoice"=>$beforeinfo['invoice'],
			"deposit"=>$beforeinfo['desposit'],
			"takeoutaddress"=>$beforeinfo['takeoutaddress'],
			"discountype"=>$beforeinfo['discountype'],
			"paystatus"=>"paid",
			"payrole"=>$beforeinfo['payrole'],
			"paystate"=>$beforeinfo['paystate'],
			"tabname"=>$tabname,
			"cusnum"=>$cusnum,
			"timestamp"=>time(),
			"billstatus"=>"done",
			"paymoney"=>$orderfee,
			"clearmoney"=>$clearmoney,
			"othermoney"=>"0",
			"discountval"=>$discountval,
			"cashmoney"=>"0",
			"unionmoney"=>"0",
			"vipmoney"=>"0",
			"discountmode"=>$discountmode,
			"ticketval"=>$ticketval,
			"ticketnum"	=>$ticketnum,
			"ticketway"=>$ticketway,
			"meituanpay"=>"0",
			"dazhongpay"=>"0",
			"nuomipay"=>"0",
			"alipay"=>"0",
			"wechatpay"=>$orderfee,
			"paytype"=>$paytype,
			"returndepositmoney"=>$returndepositmoney,
			"paymethod"=>$paymethod,
			"cashierman"=>$nickname,
			"orderrequest"=>$orderrequest,
			"food"=>$foodarr,
	);
	//     		print_r($inputarr);exit;
	
	//得到预下单商家信息
	$billshopinfoarr=$notifyurl->getOneBillShopinfo("prebillshopinfo", $billid);
	$normalbillid=$notifyurl->intoConsumeRecord($inputarr);//插入正式数据库
	
	//自动库存
	$notifyurl->updateSelfStock($normalbillid);
	
	$theday=$notifyurl->getTheday($shopid);
	$notifyurl->addBillNum($shopid, $normalbillid, $theday);
	
	//得到正式下单商家信息
	$billshopinfoarr['billid']=$normalbillid;
	if($type=="inner"){
	    $notifyurl->intoBillInnerShopinfo($billshopinfoarr, "billshopinfo");
	}else{
	    $notifyurl->intoBillShopinfo($billshopinfoarr, "billshopinfo");
	}
    
	$billarr=$notifyurl->getOneBillInfoByBillid($normalbillid);//新数据
	$consumeListArr=$notifyurl->tobeConsumeList($billarr,$paymethod,$paymoney);
	
	// 	print_r($consumeListArr);exit;//消费清单
	$consumearr=$notifyurl->printConsumeListData(json_encode($consumeListArr));
	if(!empty($consumearr)){$temparr[]=$consumearr;}
	
	//厨房单
	$billarr['printerid']="";//代表按照规则打印

	$billno=$notifyurl->getBillNum(strval($billarr['_id']));
	$billarr['billno']=$billno;
	
	$orderfoodarr=$notifyurl->orderByprinterid($billarr);
	// print_r($orderfoodarr);exit;
	$piecelistArr=$notifyurl->tobePieceList($orderfoodarr);
	// print_r($piecelistArr);exit;
	$kitchenarr=$notifyurl->PrintKitchenData(json_encode($piecelistArr));
	if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
	
	$urls=$notifyurl->getUrlsArr(json_encode($temparr));
	// 	print_r($urls);exit;
	
	$notifyurl->sendFreeMessage($urls);//打印
	if(!empty($tabid)){
		$notifyurl->updateOneTabStatus($tabid, "empty");//买单之后自动清台
	}
	//     	$notifyurl->delPrebillByBillid($billid);
	$payrecord=array(
			"out_trade_no"=>$orderno,
			"trade_no"=>"",
			"billid"	=>$normalbillid,
			"shopid"=>$shopid,
			"uid"=>$uid,
			"buyer"=>$nickname,
			"tabid"=>$tabid,
			"total_fee"=>$paymoney,
			"paytype"=>$paytype,
			"downtime"=>$billarr['timestamp'],
			"gmt_create"=>time(),
			"buyer_email"=>"",
			"gmt_payment"=>time(),
			"subject"=>"顾客：".$nickname,
			"trade_status"=>"success",
				
	);
	$notifyurl->addPayRecord($payrecord);
	//发送信息
	$notifyurl->sendSMSBillInfo($normalbillid,$type);
	if($type=="outer"){
	    $notifyurl->addBalance($shopid, $paymoney);
	}
}

?>
