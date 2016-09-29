<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
require_once ('/var/www/html/queue/Factory/InterfaceFactory.php');
//环信
require_once ('/var/www/html/emchat-server/Easemob.class.php');
require_once ('/var/www/html/emchat-server/global.php');
class FinishPay{
	public function finishPayStatus($billid,$paymoney,$paytype,$coupontype,$coupontypevalue,$coupontypenum, $status){
		PRINT_InterfaceFactory::createInstanceHandleDAL()->finishPayStatus($billid,$paymoney,$paytype,$coupontype,$coupontypevalue,$coupontypenum, $status);
	}
	public function tobeConsumeList($inputdarr){
		return PRINT_InterfaceFactory::createInstancePrintBillDAL()->tobeConsumeList($inputdarr);
	}
	public function printConsumeListData($json,$type){
		return PRINT_InterfaceFactory::createInstanceConsumeListDAL()->printConsumeListData($json, $type);
	}
	public function getUrlsArr($json){
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->getUrlsArr($json);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function getOneBillById($billid){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->getOneBillById($billid);
	}
	public function getMyShopAllServers($shopid){
		return QUEUE_InterfaceFactory::createInstanceQueueDAL()->getMyShopAllServers($shopid);
	}
	public function addPointsToUser($uid,$shopid,$paymoney){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->addPointsToUser($uid,$shopid, $paymoney);
	}
	public function changeBillStatus($billid,$paystatus,$paytype){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->changeBillStatus($billid, $paystatus, $paytype);
	}
	public function updateTabStatus($shopid,$tabname, $usestatus){
		PRINT_InterfaceFactory::createInstanceHandleDAL()->updateTabStatus($shopid,$tabname, $usestatus);
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
	public function judgeTheServer($shopid,$uid){
	    return PRINT_InterfaceFactory::createInstanceHandleDAL()->judgeTheServer($shopid, $uid);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$finishpay=new FinishPay();
$easemob=new Easemob($options);
if(isset($_POST['shopid'])){
	$billid=$_POST['billid'];
	$uid=$_POST['uid'];
	$nickname=$_POST['nickname'];
	$tabname=$_POST['tabname'];
	$paytype=$_POST['paytype'];//新增
	if($paytype=="cash"){
	    $paytype="cashpay";
	}
	$paymoney=$_POST['paymoney'];//新增
	if(empty($paymoney)){$paymoney="0";}
	$coupontype=$_POST['coupontype'];
	$coupontypevalue=$_POST['coupontypevalue'];
	$coupontypenum=$_POST['coupontypenum'];
	if(empty($coupontype)){
		$coupontype="0";
		$coupontypevalue="0";
		$coupontypenum="0";
	}
	
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$finishpay->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$billid.$uid.$nickname.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$finishpay->updateShopSession($shopid,$session);break;
			}
			$finishpay->finishPayStatus($billid,$paymoney,$paytype,$coupontype,$coupontypevalue,$coupontypenum, "finish_offlinepay");
			//打印出消费清单
			$temparr=array();
			$inputdarr=$finishpay->getOneBillById($billid);
			$consumeListArr=$finishpay->tobeConsumeList($inputdarr);
			// print_r($consumeListArr);exit;//消费清单
			$consumearr=$finishpay->printConsumeListData(json_encode($consumeListArr), "2");
			if(!empty($consumearr)){$temparr[]=$consumearr;}
			
			if(empty($temparr)){
			    header('Content-type: application/json');
			    echo   json_encode(array("paytype"=>"billdone" ,"token"=>$session));
			   $sendshopMsg="温馨提示：您未设置消费清单打印机，无法出单，请设置好后重新输入。";
			    $easemob->yy_hxSend("admin",array("shop".$shopid), $sendshopMsg, "users",array(""=>""));
			    $finishpay->changeBillStatus($billid, "billdone", $paytype);
			    return ;
			}
			
			header('Content-type: application/json');
			echo json_encode(array("paytype"=>"finish_offlinepay", "token"=>$session));
			$serverarr=$finishpay->judgeTheServer($shopid, $uid);
			if(empty($serverarr)){//不是本店服务员
			    $mypoints=$finishpay->getPoints($shopid, $uid);
			    $shoppointsetarr=$finishpay->getShopPointSet($shopid);
			    $thing="";
			    $pointtype="";
			    $thingnum="0";
			    $minuspoints=0;
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
// 			        $finishpay->MinusMyPoint($uid, $shopid, $minuspoints);//线下付款扣掉积分
			        $mypoints=$finishpay->addPointsToUser($uid,$shopid, $paymoney);//线下付款赠送积分
			    }			    
			}
			
			$sendcusMsg="您已成功买单，感谢您的光临，欢迎下次惠顾 O(∩_∩)O~";
			$easemob->yy_hxSend("shop".$shopid,array("customer".$uid), $sendcusMsg, "users",array(""=>""));
			$sendshopMsg="温馨提示：台号为".$tabname."的顾客[".$nickname."]已买单，订单状态已更新。";
			$easemob->yy_hxSend("admin",array("shop".$shopid), $sendshopMsg, "users",array(""=>""));
			$uidarr=$finishpay->getMyShopAllServers($shopid);
			if(!empty($uidarr)){
				$sendtoshopservermsg="温馨提示：台号为".$tabname."的顾客已买单。";
				$easemob->yy_hxSend("shop".$shopid,$uidarr, $sendtoshopservermsg, "users",array(""=>""));
			}
			$urls=$finishpay->getUrlsArr(json_encode($temparr));
			// print_r($urls);exit;
			$nullarr=$finishpay->sendFreeMessage($urls);//打印
			
			$finishpay->updateTabStatus($shopid, $tabname, "0");//买单之后自动清台
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$billid="548e5ddd16c1099d198b45b4";
$uid="54769d6816c10909058b4651";
$paymoney="0";
$coupontype="美团代金券";
$coupontypevalue="50";
$coupontypenum="2";

$uidarr=$finishpay->getMyShopAllServers("547430f016c10932708b4624");
print_r($uidarr);exit;
// $finishpay->finishPayStatus($billid,$paymoney,$coupontype,$coupontypevalue,$coupontypenum, "finish_offlinepay");
// exit;
$inputdarr=$finishpay->getOneBillById($billid);

$consumeListArr=$finishpay->tobeConsumeList($inputdarr);
// print_r($consumeListArr);exit;//消费清单
$consumearr=$finishpay->printConsumeListData(json_encode($consumeListArr), "2");
if(!empty($consumearr)){$temparr[]=$consumearr;}
$urls=$finishpay->getUrlsArr(json_encode($temparr));
print_r($urls);exit;
?>