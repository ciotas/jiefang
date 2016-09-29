<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
//环信
require_once ('/var/www/html/emchat-server/Easemob.class.php');
require_once ('/var/www/html/emchat-server/global.php');
class PrintClient{
	public function getTwoBillidByTabname($tabname,$shopid){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->getTwoBillidByTabname($tabname,$shopid);
	}
	public function orderByprinterid($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->orderByprinterid($inputdarr);
	}
	public function tobeRunner($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeRunner($inputdarr);
	}
	public function tobeCusList($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeCusList($inputdarr);
	}
	public function tobeConsumeList($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr);
	}
	public function tobePieceList($arr){
		return PRINT_InterfaceFactory::createInstancePieceListDAL()->tobePieceList($arr);
	}
	public function intoConsumeRecord($inputdarr,$billstatus){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->intoConsumeRecord($inputdarr,$billstatus);
	}
	public function intoBillRecord($inputarr, $billstatus){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->intoBillRecord($inputarr, $billstatus);
	}
	public function printChuanCaiData($type,$json){
		return PRINT_InterfaceFactory::createInstanceRunnerWorkerDAL()->printChuanCaiData($type,$json);
	}
	public function comBineBill($billid,$uid){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->comBineBill($billid,$uid);
	}
	public function printCuslistData($type,$json){
		return PRINT_InterfaceFactory::createInstanceCusListWorkerDAL()->printCuslistData($type, $json);
	}
	public function printConsumeListData($json,$type){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json, $type);
	}
	public function changeBillStatus($billid,$paystatus,$paytype){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->changeBillStatus($billid, $paystatus, $paytype);
	}
	public function PrintKitchenData($json, $type){
		return PRINT_InterfaceFactory::createInstanceKitchenWorkerDAL()->PrintKitchenData($json, $type);
	}
	public function returnCoupon($uid,$shopid,$couponvalue,$couponnum){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->returnCoupon($uid, $shopid, $couponvalue, $couponnum);
	}
	public function getUrlsArr($json){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function updateTabStatus($shopid,$tabname, $usestatus){
		PRINT_InterfaceFactory::createInstanceHandleDAL()->updateTabStatus($shopid,$tabname, $usestatus);
	}
	public function updateBillStatus($billid,$billstatus){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->updateBillStatus($billid, $billstatus);
	}
	public function getCusinfo($uid,$shopid){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->getCusinfo($uid,$shopid);
	}
	public function getShopBriefInfo($shopid){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->getShopBriefInfo($shopid);
	}
	public function addPointsToUser($uid,$shopid,$paymoney){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->addPointsToUser($uid,$shopid, $paymoney);
	}
	public function getSendMsg($shopid,$uid,$totalmoney,$paytype,$disacountfoodmoney,$discountvalue,$type,$discountzong,$paymoney,$time){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getSendMsg($shopid, $uid, $totalmoney, $paytype,$disacountfoodmoney, $discountvalue, $type, $discountzong, $paymoney, $time);
	}
	public function MinusMyPoint($uid, $shopid, $minuspoint){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->MinusMyPoint($uid, $shopid, $minuspoint);
	}
	public function getPoints($shopid,$uid){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->getPoints($shopid, $uid);
	}
	public function getShopPointSet($shopid){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->getShopPointSet($shopid);
	}
	public function getFoodnameByFid($foodid){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->getFoodnameByFid($foodid);
	}
	public function getThePointPrinter($inputarr,$shopid,$foodid){
	    return PRINT_InterfaceFactory::createInstanceToBePointDAL()->getThePointPrinter($inputarr, $shopid, $foodid);
	}
	public function judgeTheServer($shopid,$uid){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->judgeTheServer($shopid, $uid);
	}
	public function getTobePointOther($inputarr){
	    return PRINT_InterfaceFactory::createInstanceToBePointDAL()->getTobePointOther($inputarr);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$printclient=new PrintClient();
$easemob=new Easemob($options);
if(isset($_POST['shopid']) && isset($_POST['uid'])){
	$shopid=$_POST['shopid'];
	$uid=$_POST['uid'];
	$tabname=$_POST['tabname'];
	$wait=$_POST['wait'];
	$type=$_POST['type'];
	$paytype=$_POST['paytype'];//alipay,offlinepay，以后要改为paystatus
	$discountvalue=$_POST['discountvalue'];
	$discountzong=$_POST['discountzong'];
// 	$minuspoint=$_POST['minuspoint'];//新增
	$discounttitle=$_POST['discounttitle'];
	$discountdesc=$_POST['discountdesc'];
	$cusnum=$_POST['cusnum'];
	$totalmoney=$_POST['foodtotalmoney'];
	$disacountmoney=$_POST['discountmoney'];
	$disacountfoodmoney=$_POST['disacountfoodmoney'];
	$paymoney=$_POST['paymoney'];
	$tradeno=$_POST['tradeno'];//交易号
	$food=$_POST['food'];
	$service=$_POST['service'];
	$servicearr=json_decode($service,true);
	$timestamp=$_POST['timestamp'];//下单时间
	$time=time();//打印时间
	$signature=$_POST['signature'];
	$sessionresult=$printclient->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$cusnum.$wait.$type.$discountvalue.$totalmoney.$paymoney.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$printclient->updateCusSession($uid, $session);
			}
			if($totalmoney<="0"){
				$totalmoney="0.01";
			}
			//得到用户名
			$cusinfoarr=$printclient->getCusinfo($uid,$shopid);
			$nickname=$cusinfoarr['nickname'];
			//得到商店名
			$shopinfoarr=$printclient->getShopBriefInfo($shopid);
			$shopname=$shopinfoarr['shopname'];
			$branchname=$shopinfoarr['branchname'];
			
			$foodarr=json_decode($food,true);
			if(empty($foodarr)){
				header('Content-type: application/json');
				echo json_encode(array("status"=>"empty","token"=>$session));exit;
			}
			if($type=="coupon"){
				$couponarr=explode('*', $discountvalue);
				$couponvalue=$couponarr[0];
				$couponnum=$couponarr[1];
				$printclient->returnCoupon($uid, $shopid, $couponvalue, $couponnum);
			}
			
			
			if($paytype=="alipay"){
			    $paystatus="finish_onlinepay";
			}elseif ($paytype=="offlinepay"){
			    $paystatus="offlinepay";
			}
			$inputdarr=array(
					"uid"=>$uid,
					"shopid"=>$shopid,
					"nickname"=>$nickname,
					"shopname"=>$shopname,
					"branchname"=>$branchname,
					"wait"=>$wait,
					"type"=>$type,
					"paytype"=>$paytype,
			        "paystatus"=>$paystatus,
					"tabname"=>$tabname,
					"cusnum"=>$cusnum,
			         "pointstr"=>$pointstr,
					"totalmoney"=>$totalmoney,
					"disacountmoney"=>$disacountmoney,
					"disacountfoodmoney"=>$disacountfoodmoney,
					"paymoney"=>$paymoney,
					"discounttitle"=>$discounttitle,
					"discountvalue"=>$discountvalue,
					"discountzong"=>$discountzong,
					"discountdesc"=>$discountdesc,
					"time"=>time(),
					"timestamp"=>$timestamp,
					"tradeno"=>$tradeno,
					"food"=>$foodarr,
					"service"=>$servicearr
			);
// 			header('Content-type: application/json');
// 			echo json_encode($inputdarr);exit;
            
			//台号营业状态
			$usestatus="0";
			if($tabname!="待定" || $tabname!="未知"  || !empty($tabname)){//台号确定，改为已占用
				$usestatus="1";
				$printclient->updateTabStatus($shopid,$tabname, $usestatus);
			}
			
			$temparr=array();
			//消费记录入库
			$billstatus="0";//默认状态为0，表示已下单
			$billid=$printclient->intoConsumeRecord($inputdarr,$billstatus);
			//判断是否是服务员
			$istheserver=$printclient->judgeTheServer($shopid, $uid);
			if($paytype=="alipay" || !empty($istheserver)){
			    if(empty($istheserver)&&!empty($shoppointsetarr)){//不为服务员
			        $printclient->MinusMyPoint($uid, $shopid, $minuspoints);//减去积分
			        $mypoints=$printclient->addPointsToUser($uid,$shopid, $paymoney);//赠送积分
			        //打印积分兑换单
			        $pointarr=array();
			        if($pointtype=="food"){
			            $pointarr=$printclient->getThePointPrinter($inputdarr, $shopid, $thing);
			        }elseif ($pointtype=="other"){
			            $pointarr=$printclient->getTobePointOther($inputdarr);
			        }
			        $printclient->sendFreeMessage($pointarr);
			    }
// 				$printclient->intoBillRecord($inputdarr, $billstatus);
				$foodRunnerArr=$printclient->tobeRunner($inputdarr);//传菜单
				// print_r($foodRunnerArr);exit;
				$chuancaiarr=$printclient->printChuanCaiData("3", json_encode($foodRunnerArr));
				if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
				// print_r($temparr);exit;
				$cusListArr=$printclient->tobeCusList($inputdarr);//划菜单
				// print_r($cusListArr);exit;
				$cuslistarr=$printclient->printCuslistData("1", json_encode($cusListArr));
				if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
				
				if($paytype=="alipay"){
				    $consumeListArr=$printclient->tobeConsumeList($inputdarr);
				    // print_r($consumeListArr);exit;//消费清单
				    $consumearr=$printclient->printConsumeListData(json_encode($consumeListArr), "2");
				    if(!empty($consumearr)){$temparr[]=$consumearr;}
				}
				
				$orderfoodarr=$printclient->orderByprinterid($inputdarr);
				// print_r($orderfoodarr);exit;
				$piecelistArr=$printclient->tobePieceList($orderfoodarr);
				// print_r($piecelistArr);exit;
				$kitchenarr=$printclient->PrintKitchenData(json_encode($piecelistArr), "kitchen");
				if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
				if(!empty($istheserver)){//为服务员，改变状态
				    $printclient->changeBillStatus($billid, "billdone", "offlinepay");
				    $printclient->getTwoBillidByTabname($tabname, $shopid);
				}else{
				    $printclient->comBineBill($billid,$uid);//合并单子
				}
			 }
			$urls=$printclient->getUrlsArr(json_encode($temparr));
			// print_r($urls);exit;
			$nullarr=$printclient->sendFreeMessage($urls);
			if(!empty($nullarr)){
				foreach ($nullarr as $key=>$status){
					$temparr=explode('|', $key);
					$devicearr[]=array("outputtype"=>$temparr[1],"deviceno"=>$temparr[2],"devicekey"=>$temparr[3],"printstatus"=>$status);
				}
				//即时通讯发送信息给商家
				$printclient->updateBillStatus($billid, json_encode($devicearr));//表示下单失败,记录
			}else{
				$printclient->updateBillStatus($billid, "YES");//修改下单billstatus状态,"YES"表示下单成功
			}
			
			header('Content-type: application/json');
			echo json_encode(array("status"=>"ok","token"=>$session));//给顾客返回的是统一成功，把错误发给商家
			//发送信息的内容
			if($tabname=="未设置" || $tabname=="待定" || $tabname==""){
				$beforetips="提前点菜";
			}else{
				$beforetips="台号为".$tabname;
			}
			$sendcusMsg="";
			$sendshopMsg="";
			

			if($paytype=="offlinepay"){//用户选择线下支付，提醒商家
				$sendcusMsg=date("Y-m-d H:i:s",time())."下单成功!";
				$sendshopMsg=date("Y-m-d H:i:s",time())."来新单了！";
			}elseif($paytype=="alipay"){
// 				$sendcusMsg="您已选择线下买单，请在用晚餐后呼叫服务员买单。";
                if($discountzong>0.01){
                    $desc="，本次消费使用了优惠，无法享受退菜服务，敬请谅解！";
                }else{
                    $desc="";
                }
                $sendcusMsg=date("Y-m-d H:i:s",time())."下单成功!";
                $sendshopMsg=date("Y-m-d H:i:s",time())."来新单了！";
			}
// 			$sendcusMsg.=$printclient->getSendMsg($shopid, $uid, $totalmoney,$paytype, $disacountfoodmoney, $discountvalue, $type, $discountzong, $paymoney, $time);
			$easemob->yy_hxSend("admin",array("shop".$shopid), $sendshopMsg, "users",array(""=>""));
// 			if(empty($istheserver)){
		    $easemob->yy_hxSend("shop".$shopid,array("customer".$uid), $sendcusMsg, "users",array(""=>""));
		    if($wait=="1"){//等叫，提前点菜
		       $sendcusMsg="您已使用提前点菜功能，如需要正式上菜，请联系服务员。";
		        $easemob->yy_hxSend("shop".$shopid,array("customer".$uid), $sendcusMsg, "users",array(""=>""));
		    }
// 			}
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}	
}

exit;
$shopid="547430f016c10932708b4624";
$uid="54769d6816c10909058b4651";
$totalmoney="30.08";
$disacountfoodmoney="30.02";
$discountvalue="";
$type="point";
$discountzong="100";
$paymoney="89.8";
// $cusinfoarr=$printclient->getCusinfo($uid,$shopid);

// $mypoints=$printclient->getPoints($shopid, $uid);
// $shoppointsetarr=$printclient->getShopPointSet($shopid);
$thing="";
$pointtype="";
$thingnum="0";
$printclient->changeBillStatus($billid, "billdone", "offlinepay");
$printclient->getTwoBillidByTabname("A01", "547430f016c10932708b4624");
exit;
if(!empty($shoppointsetarr)){
			    if($shoppointsetarr['type']=="money"){//判断mypoints
			        $allthing=sprintf("%.0f",($shoppointsetarr['thing']/$shoppointsetarr['points'])*$mypoints);//如果全部兑换
			        if($allthing>$disacountfoodmoney){
			            //可兑换的积分
			            $minuspoints=round($disacountfoodmoney*($shoppointsetarr['points']/$shoppointsetarr['thing']));
			        }else{
			            //可兑换的积分
			            $minuspoints=$mypoints;
			        }
			    }else{
			        if($mypoints>=intval($shoppointsetarr['points'])){
			            $thing=$shoppointsetarr['thing'];
			            $pointtype=$shoppointsetarr['type'];
			            $thingnum=$shoppointsetarr['num'];
			            $minuspoints=$shoppointsetarr['points'];
			        }
			    }
			}
//减去积分和打印
$pointstr="";
if(!empty($pointtype)){
    switch ($pointtype){
        case "money"://减去积分
            $printclient->MinusMyPoint($uid, $shopid, $shoppointsetarr['points']);
            break;
        case "food"://打印出来
            $foodarr=$printclient->getFoodnameByFid($thing);
            if(!empty($foodarr)){
                $pointstr=$shoppointsetarr['points']."积分，兑换".$thingnum.$foodarr['foodunit'].$foodarr['foodname'];
            }
            break;
        case "other"://打印出来
            $pointstr=$shoppointsetarr['points']."积分，兑换".$thingnum."份".$thing;
            break;
    }
}

$inputdarr=array(
    "uid"=>$uid,
    "shopid"=>$shopid,
    "nickname"=>$cusinfoarr['nickname'],
    "shopname"=>"aaaa",
    "branchname"=>"jjjjjj",
    "wait"=>"1",
    "type"=>$type,
    "paytype"=>"alipay",
    "tabname"=>"A01",
    "cusnum"=>"4",
    "pointstr"=>$pointstr,
    "totalmoney"=>$totalmoney,
    "disacountmoney"=>"20",
    "disacountfoodmoney"=>"80",
    "paymoney"=>$paymoney,
    "discounttitle"=>"aaa",
    "discountvalue"=>"50*3",
    "discountzong"=>"0",
    "discountdesc"=>"qqqqq",
    "time"=>time(),
    "timestamp"=>time(),
    "tradeno"=>"123456",
//     "food"=>$foodarr,
//     "service"=>$servicearr
);
$temparr=array();
$foodRunnerArr=$printclient->tobeRunner($inputdarr);//传菜单
// print_r($foodRunnerArr);exit;
$chuancaiarr=$printclient->printChuanCaiData("3", json_encode($foodRunnerArr));
if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
print_r($temparr);exit;
//打印积分兑换单
$pointarr=array();
$pointtype="other";
if($pointtype=="food"){
    $pointarr=$printclient->getThePointPrinter($inputdarr, $shopid, $thing);
}elseif ($pointtype=="other"){
    $pointarr=$printclient->getTobePointOther($inputdarr);
}
// print_r($pointarr);exit;
$printclient->sendFreeMessage($pointarr);

echo $mypoints;exit;
// print_r($cusinfoarr);exit;
$sendcusMsg=$printclient->getSendMsg($shopid, $uid, $totalmoney, $disacountfoodmoney, $discountvalue, $type, $discountzong, $paymoney, time());
echo $sendcusMsg;
exit;
$food='{"food":[{"foodid":"53a8f20316c109cb5a8b4569","foodname":"水煮肉",
		"foodamount":"1","zonename":"中厨","foodguqing":"0", "fooddisaccount":"1",
		"cooktype":"红烧" ,"foodprice":"22.50","foodunit":"例", "foodrequest":"微辣",
		 "printerid":"5438e04516c1090a058b45cc"},
{"foodid":"53a8f23d16c10919218b4568","foodname":"烤鸭",
		"foodamount":"1","zonename":"点心房", "foodprice":"27.00","foodunit":"例","foodrequest":"少盐",
		"cooktype":"清蒸","foodguqing":"0", "fooddisaccount":"1",
		"printerid":"5438e04516c1090a058b45cc"}]}';
// print_r(json_decode($food,true));exit;
$service='{"service":[{"itemname":"\u5927\u5385\u8336\u6c34\u8d39",
		"itemprice":"2","chargestyle":"1"},
{"itemname":"\u9910\u524d\u6c34\u679c","itemprice":"0.01","chargestyle":"0"}]}';
$foodarr=json_decode($food,true);
// print_r($foodarr);exit;
$servicearr=json_decode($service,true);
if(empty($foodarr)){
	echo json_encode(array("status"=>"empty","token"=>""));exit;
}

$shopid="540eaf1716c1090b058b4589";
$uid="541fd6be16c10909058b45a5";
//得到用户名
$cusinfoarr=$printclient->getCusinfo($uid);
$nickname=$cusinfoarr['nickname'];
// echo $nickname." ";
//得到商店名
$shopinfoarr=$printclient->getShopBriefInfo($shopid);
$shopname=$shopinfoarr['shopname'];
$branchname=$shopinfoarr['branchname'];
// echo $shopname." ".$branchname;exit;
$wait="1";//等叫
$type="coupon";
$tabname="A12";
$cusnum="4";
$totalmoney="100";
$disacountmoney="80";
$paymoney="20";
$discounttitle="使用优惠券";
$discountvalue="50*2";
$discountzong="100";
$discountdesc="50*2";
$orderno="201411130908234598";//单号
$disacountfoodmoney="100";
$time=time();
$inputdarr=array(
			"uid"=>$uid,
			"shopid"=>$shopid,
			"nickname"=>$nickname,
			"shopname"=>$shopname,
			"branchname"=>$branchname,
			"wait"=>$wait,
			"type"=>$type,
			"tabname"=>$tabname,
			"cusnum"=>$cusnum,
			"totalmoney"=>$totalmoney,
			"disacountmoney"=>$disacountmoney,
			"paymoney"=>$paymoney,
			"discounttitle"=>$discounttitle,
			"discountvalue"=>$discountvalue,
			"discountzong"=>$discountzong,
			"discountdesc"=>"aaaa",
			"time"=>$time,
			"orderno"=>$orderno,
			"food"=>$foodarr,
			"service"=>$servicearr
	);
// print_r($arr);exit;
$sendcontent=$printclient->getSendMsg($shopid, $uid, $totalmoney, $disacountfoodmoney, $discountvalue, $type, $discountzong, $paymoney, $time);

echo $sendcontent;exit;
$temparr=array();
//消费记录入库
$billstatus="0";//默认状态为0，表示已下单，暂时只有这一个状态
$billid="5438db1316c10949118b4567";
// $billid=$printclient->intoConsumeRecord($inputdarr,$billstatus);
// echo $billid;exit;

$foodRunnerArr=$printclient->tobeRunner($inputdarr);//传菜单
// print_r($foodRunnerArr);exit;
$chuancaiarr=$printclient->printChuanCaiData("3", json_encode($foodRunnerArr));
if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
// print_r($temparr);exit;

$cusListArr=$printclient->tobeCusList($inputdarr);//客看单
$cuslistarr=$printclient->printCuslistData("1", json_encode($cusListArr));
if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
// print_r($temparr);exit;

$consumeListArr=$printclient->tobeConsumeList($inputdarr);
// print_r($consumeListArr);exit;//消费清单
$consumearr=$printclient->printConsumeListData(json_encode($consumeListArr), "2");
if(!empty($consumearr)){$temparr[]=$consumearr;}
// print_r($temparr);exit;

$orderfoodarr=$printclient->orderByprinterid($inputdarr);
// print_r($orderfoodarr);exit;
$piecelistArr=$printclient->tobePieceList($orderfoodarr);
// print_r($piecelistArr);exit;
$kitchenarr=$printclient->PrintKitchenData(json_encode($piecelistArr), "kitchen");
if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
// print_r($temparr);exit;

if(empty($temparr)){
	header('Content-type: application/json');
	echo   json_encode(array("status"=>"empty","token"=>""));
	return ;
}
// $temparr['billid']=$billid;
// print_r($temparr);exit;
/******************************************************************/
$urls=$printclient->getUrlsArr(json_encode($temparr));
// print_r($urls);exit;
$nullarr=$printclient->sendFreeMessage($urls);
if(!empty($nullarr)){
	foreach ($nullarr as $key=>$status){
		$temparr=explode('|', $key);
		$devicearr[]=array("outputtype"=>$temparr[1],"deviceno"=>$temparr[2],"devicekey"=>$temparr[3],"printstatus"=>$status);
	}
	//发送信息给商家
	
	$printclient->updateBillStatus($billid, json_encode($devicearr));//表示下单失败,记录
}else{
	$printclient->updateBillStatus($billid, "YES");//修改下单billstatus状态,"YES"表示下单成功
}
/******************************************************************/
header('Content-type: application/json');
echo json_encode(array("status"=>"ok","token"=>""));//给顾客返回的是统一成功，把错误发给商家
// $exchange->publish(2, $shopid);
// $exchange->publish(json_encode($temparr), $shopid);
// $exchange->publish(2, $shopid);
// $connection->disconnect();
return ;
?>