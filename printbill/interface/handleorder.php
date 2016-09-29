<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
//环信
require_once ('/var/www/html/emchat-server/Easemob.class.php');
require_once ('/var/www/html/emchat-server/global.php');
class HandleBill{
	public function getBillById($billid){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getBillById($billid);
	}
	public function tobeRunner($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeRunner($inputdarr);
	}
	public function tobeCusList($inputarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeCusList($inputarr);
	}
	public function tobeConsumeList($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr);
	}
	public function printChuanCaiData($type,$json){
		return PRINT_InterfaceFactory::createInstanceRunnerWorkerDAL()->printChuanCaiData($type, $json);
	}
	public function printCuslistData($type,$json){
		return PRINT_InterfaceFactory::createInstanceCusListWorkerDAL()->printCuslistData($type, $json);
	}
	public function printConsumeListData($json,$type){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json,$type);
	}
	public function PrintKitchenData($json, $type){
		return PRINT_InterfaceFactory::createInstanceKitchenWorkerDAL()->PrintKitchenData($json, $type);
	}
	public function tobePieceList($arr){
		return PRINT_InterfaceFactory::createInstancePieceListDAL()->tobePieceList($arr);
	}
	public function getThePointPrinter($inputarr,$shopid,$foodid){
	    return PRINT_InterfaceFactory::createInstanceToBePointDAL()->getThePointPrinter($inputarr, $shopid, $foodid);
	}
	public function getTobePointOther($inputarr){
	    return PRINT_InterfaceFactory::createInstanceToBePointDAL()->getTobePointOther($inputarr);
	}
	public function checkMenu($shopid,$food){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->checkMenu($shopid, $food);
	}
	public function judgeTheServer($shopid,$uid){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->judgeTheServer($shopid, $uid);
	}
	public function orderByprinterid($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->orderByprinterid($inputdarr);
	}
	public function updateBillPayStatus($billid, $status,$paystatus){
		PRINT_InterfaceFactory::createInstanceHandleDAL()->updateBillPayStatus($billid, $status,$paystatus);
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
	public function getPoints($shopid,$uid){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->getPoints($shopid, $uid);
	}
	public function getFoodnameByFid($foodid){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->getFoodnameByFid($foodid);
	}
	public function getShopPointSet($shopid){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->getShopPointSet($shopid);
	}
	public function MinusMyPoint($uid, $shopid, $minuspoint){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->MinusMyPoint($uid, $shopid, $minuspoint);
	}
	public function comBineBill($billid,$uid){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->comBineBill($billid,$uid);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$handlebill=new HandleBill();
$easemob=new Easemob($options);
if(isset($_POST['billid'])){
	$shopid=$_POST['shopid'];
	$billid=$_POST['billid'];
	$choice=$_POST['choice'];
	$paytype=$_POST['paytype'];
	$paystatus="billdone";
	$uid=$_POST['uid'];
	$nickname=$_POST['nickname'];
	$tabname=$_POST['tabname'];//新增
	$printerarr=json_decode($choice,true);
	
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$handlebill->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$billid.$choice.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$handlebill->updateShopSession($shopid,$session);break;
			}
			if(!empty($billid)){
				$arr=$handlebill->getBillById($billid);
				//var_dump($result);exit;				
				if(empty($arr)){
					header('Content-type: application/json');
					echo json_encode(array("status"=>"choose_empty","paytype"=>$paytype, "token"=>$session));exit;
				}
				$temparr=array();
				foreach ($printerarr as $key=>$val){
					switch ($val['outputtype']){
						case "1":
							$cusListArr=$handlebill->tobeCusList($arr);//客看单
							$cuslistarr=$handlebill->printCuslistData( $val['outputtype'], json_encode($cusListArr));
							if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
							break;
						case "2":
							$arr['discountzong']=$arr['disacountmoney'];
							$consumeListArr=$handlebill->tobeConsumeList($arr);
							$consumearr=$handlebill->printConsumeListData(json_encode($consumeListArr),$val['outputtype']);
							if(!empty($consumearr)){$temparr[]=$consumearr;}
							break;
						case "3":
							$foodRunnerArr=$handlebill->tobeRunner($arr);//传菜单
							$chuancaiarr=$handlebill->printChuanCaiData($val['outputtype'], json_encode($foodRunnerArr));
							if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
							break;
						default:
						    if($val['outputtype']=="4" || $val['outputtype']=="5" || $val['outputtype']=="6"){
						        $orderfoodarr=$handlebill->orderByprinterid($arr);
						        $piecelistArr=$handlebill->tobePieceList($orderfoodarr);
						        $kitchenarr=$handlebill->PrintKitchenData(json_encode($piecelistArr), "kitchen");
						        if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
						    }
							break;
					}
				}
				
				if(empty($temparr)){
					header('Content-type: application/json');
					echo  json_encode(array("status"=>"empty","paytype"=>$paytype,"token"=>$session));
					$sendshopMsg="温馨提示：请在设置中启用打印机开关后重试";
// 					$sendshopMsg=
					$easemob->yy_hxSend("admin",array("shop".$shopid), $sendshopMsg, "users",array(""=>""));
					exit;
				}
				//积分部分
				$mypoints=$handlebill->getPoints($shopid, $uid);
				$shoppointsetarr=$handlebill->getShopPointSet($shopid);
                
				$thing="";
				$pointtype="";
				$thingnum="0";
				$pointarr=array();
				$inputdarr=array();
				$pointstr="";
				if(!empty($shoppointsetarr)){
				    if($shoppointsetarr['type']=="money"){
// 				        $minuspoints=$mypoints;
				        $allthing=sprintf("%.0f",($shoppointsetarr['thing']/$shoppointsetarr['points'])*$mypoints);//如果全部兑换
				        if($allthing>$arr['disacountfoodmoney']){
				            //可兑换的积分
				            $minuspoints=round($arr['disacountfoodmoney']*($shoppointsetarr['points']/$shoppointsetarr['thing']));
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
				    $handlebill->MinusMyPoint($uid, $shopid, $minuspoints);//线下付款扣掉积分
				}
				$serverarr=$handlebill->judgeTheServer($shopid, $uid);//如果是服务员操作，则不进行积分操作
				if(!empty($pointtype)&&empty($serverarr)&&!empty($shoppointsetarr)){
				    switch ($pointtype){
				        case "money"://减去积分
				            break;
				        case "food"://打印出来
				            $foodinfoarr=$handlebill->getFoodnameByFid($thing);
				            if(!empty($foodinfoarr)){
				                $pointstr=$shoppointsetarr['points']."积分兑换".$thingnum.$foodinfoarr['foodunit'].$foodinfoarr['foodname'];
				            }
				            break;
				        case "other"://打印出来
				            $pointstr=$shoppointsetarr['points']."积分兑换".$thingnum."份".$thing;
				            break;
				    }
				    
			     }
				if(!empty($pointstr)){
				    $inputdarr=array(
				        "shopid"=>$shopid,
				        "nickname"=>$nickname,
				        "cusnum"=>$arr['cusnum'],
				        "pointstr"=>$pointstr,
				        "timestamp"=>$timestamp
				    );
				}
				if(!empty($thing)&&!empty($inputdarr)){//得到积分兑换的内容
				    if($pointtype=="food"){
				        $pointarr=$handlebill->getThePointPrinter($inputdarr, $shopid, $thing);
				    }elseif ($pointtype=="other"){
				        $pointarr=$handlebill->getTobePointOther($inputdarr);
				    }
				}
				//打印积分兑换单
				$handlebill->sendFreeMessage($pointarr);
				
				if($paytype=="offlinepay"){
					$billstatus="offlinepay";
					$paystatus="billdone";
					$sendcusMsg="顾客你好，已确认您的点菜单并下单到厨房，请稍等片刻，谢谢~";
					$easemob->yy_hxSend("shop".$shopid,array("customer".$uid), $sendcusMsg, "users",array(""=>""));
					$sendshopMsg="温馨提示：".$nickname."的点菜单已下单到厨房，请您在顾客结账时，及时更新订单状态，谢谢。";
					$easemob->yy_hxSend("admin",array("shop".$shopid), $sendshopMsg, "users",array(""=>""));
				}elseif($paytype=="billdone"){
					$billstatus="offlinepay";
					$paystatus="billdone";
				}elseif($paytype=="alipay"){
					$billstatus="alipay";
					$paystatus="finish_onlinepay";
				}elseif ($paytype=="finish_offlinepay"){
					$billstatus="";
					$paystatus="finish_offlinepay";
				}
				header('Content-type: application/json');
				echo json_encode(array("status"=>"ok","paytype"=>"billdone", "token"=>$session));
				$handlebill->updateBillPayStatus($billid, $billstatus,$paystatus);
				//下单打印
				$urls=$handlebill->getUrlsArr(json_encode($temparr));
				$nullarr=$handlebill->sendFreeMessage($urls);
				//更新台号状态
				$handlebill->updateTabStatus($shopid,$tabname, "1");
				//合并已下单尚未付款的单子，给服务员用暂时不合并单子
				if(empty($serverarr)){//不是服务员——合并
				    $handlebill->comBineBill($billid,$uid);
				}
			}
		}
	}else{
		header('Content-type: application/json');
		echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
	}
}

exit;
$shopid="547430f016c10932708b4624";
$uid="54769d6816c10909058b4651";
$billid="54af8a7c5bc109a1058b456d";
// $handlebill->comBineBill($billid,$uid);exit;
//测试
$printerarr=array(
	"0"=>array(
			"deviceno"=>"814070204",
			"devicekey"=>"4YBUBb1V",
			"outputtype"=>"4"
	),
    "1"=>array(
        "deviceno"=>"814070204",
        "devicekey"=>"4YBUBb1V",
        "outputtype"=>"waiting"
    ),
);
// echo json_encode($printerarr);exit;
$arr=$handlebill->getBillById("54a1830716c10908058b4645");

//积分部分
$mypoints=$handlebill->getPoints($shopid, $uid);
$shoppointsetarr=$handlebill->getShopPointSet($shopid);

$thing="";
$pointtype="";
$thingnum="0";
$pointarr=array();
if(!empty($shoppointsetarr)){
    if($shoppointsetarr['type']=="money"){
        $minuspoints=$mypoints;
    }else{
        if($mypoints>=intval($shoppointsetarr['points'])){
            $thing=$shoppointsetarr['thing'];
            $pointtype=$shoppointsetarr['type'];
            $thingnum=$shoppointsetarr['num'];
            $minuspoints=$shoppointsetarr['points'];
        }
    }
}

$pointstr="";
if(!empty($pointtype)){
    switch ($pointtype){
        case "money"://减去积分
            break;
        case "food"://打印出来
            $foodinfoarr=$handlebill->getFoodnameByFid($thing);
            if(!empty($foodinfoarr)){
                $pointstr=$shoppointsetarr['points']."积分，兑换".$thingnum.$foodinfoarr['foodunit'].$foodinfoarr['foodname'];
            }
            break;
        case "other"://打印出来
            $pointstr=$shoppointsetarr['points']."积分，兑换".$thingnum."份".$thing;
            break;
    }
}
$inputdarr=array(
    "shopid"=>$shopid,
    "nickname"=>"aa",
    "cusnum"=>"4",
    "pointstr"=>$pointstr,
    "timestamp"=>time()
);
// print_r($inputdarr);exit;
if(!empty($thing)){//得到积分兑换的内容
    if($pointtype=="food"){
        $pointarr=$handlebill->getThePointPrinter($inputdarr, $shopid, $thing);
    }elseif ($pointtype=="other"){
        $pointarr=$handlebill->getTobePointOther($inputdarr);
    }
}
// print_r($pointarr);
// exit;
// print_r($printerarr);exit;
$temparr=array();
foreach ($printerarr as $key=>$val){
			switch ($val['outputtype']){
				case "1":
					$cusListArr=$handlebill->tobeCusList($arr);//客看单
// 					print_r($cusListArr);exit;
					$cuslistarr=$handlebill->printCuslistData( $val['outputtype'], json_encode($cusListArr));
// 					print_r($cuslistarr);exit;
					if(!empty($cuslistarr)){$temparr[]=$cuslistarr;}
					break;
				case "2":
					$arr['discountzong']=$arr['disacountmoney'];
// 					print_r($arr);exit;
					$consumeListArr=$handlebill->tobeConsumeList($arr);
// 					print_r($consumeListArr);exit;
					$consumearr=$handlebill->printConsumeListData(json_encode($consumeListArr),$val['outputtype']);
// 					print_r($consumearr);exit;
					if(!empty($consumearr)){$temparr[]=$consumearr;}
					break;
				case "3":
					$foodRunnerArr=$handlebill->tobeRunner($arr);//传菜单
// 					print_r($foodRunnerArr);exit;
					$chuancaiarr=$handlebill->printChuanCaiData($val['outputtype'], json_encode($foodRunnerArr));
// 					print_r($chuancaiarr);exit;
					if(!empty($chuancaiarr)){$temparr[]=$chuancaiarr;}
					break;
				default:
				    if($val['outputtype']=="4" || $val['outputtype']=="5" || $val['outputtype']=="6"){
				        $orderfoodarr=$handlebill->orderByprinterid($arr);
				        $piecelistArr=$handlebill->tobePieceList($orderfoodarr);
				        $kitchenarr=$handlebill->PrintKitchenData(json_encode($piecelistArr), "kitchen");
				        if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
				    }
					break;
			}
			print_r($temparr);
		}
// 		print_r($temparr);
		exit;
		if(empty($temparr)){
				header('Content-type: application/json');
				echo   json_encode(array("status"=>"empty"));
				exit ;
		}
		$urls=$handlebill->getUrlsArr(json_encode($temparr));
		$nullarr=$handlebill->sendFreeMessage($urls);
		
		header('Content-type: application/json');
		echo json_encode(array("status"=>"ok"));
		return ;
?>