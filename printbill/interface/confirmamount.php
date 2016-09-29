<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class ConfirmAmount{
	public function confrimFoodAmount($billid, $foodid, $foodamount,$foodnum,$cooktype){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->confrimFoodAmount($billid, $foodid, $foodamount,$foodnum,$cooktype);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
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
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function getMatchFoodInfo($foodarr,$foodid,$foodamount,$cooktype){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getMatchFoodInfo($foodarr, $foodid, $foodamount, $cooktype);
	}
}
$confirmamount=new ConfirmAmount();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$billid=$_POST['billid'];
	$foodid=$_POST['foodid'];
	$foodamount=$_POST['foodamount'];
	$foodnum =$_POST['foodnum'];
	$cooktype=$_POST['cooktype'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$confirmamount->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$billid.$foodid.$foodamount.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$confirmamount->updateCusSession($uid,$session);break;
			}
			$confirmamount->confrimFoodAmount($billid, $foodid, $foodamount,$foodnum,$cooktype);
			$billarr=$confirmamount->getOneBillInfoByBillid($billid);
			$foodarr=$confirmamount->getMatchFoodInfo($billarr['food'], $foodid, $foodamount, $cooktype);
			$billarr['food']=$foodarr;
			//厨房单
			$billarr['printerid']="";//代表按照规则打印
			$orderfoodarr=$confirmamount->orderByprinterid($billarr);
			// print_r($orderfoodarr);exit;
			$piecelistArr=$confirmamount->tobePieceList($orderfoodarr);
			// print_r($piecelistArr);exit;
			$kitchenarr=$confirmamount->PrintKitchenData(json_encode($piecelistArr));
			if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
			// print_r($kitchenarr);exit;
			$urls=$confirmamount->getUrlsArr(json_encode($temparr));
			$confirmamount->sendFreeMessage($urls);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$billid="566020c85bc109ab5c8b5408";
$foodid="554b05595bc109dd518b45c3";
$foodamount="1";
$cooktype="";
$billarr=$confirmamount->getOneBillInfoByBillid($billid);
// print_r($billarr);exit;
$foodarr=$confirmamount->getMatchFoodInfo($billarr['food'], $foodid, $foodamount, $cooktype);
$billarr['food']=$foodarr;
// print_r($billarr);exit;
//厨房单
$billarr['printerid']="";//代表按照规则打印
$orderfoodarr=$confirmamount->orderByprinterid($billarr);
// print_r($orderfoodarr);exit;
$piecelistArr=$confirmamount->tobePieceList($orderfoodarr);
// print_r($piecelistArr);exit;
$kitchenarr=$confirmamount->PrintKitchenData(json_encode($piecelistArr));
if(!empty($kitchenarr)){$temparr[]=$kitchenarr;}
// print_r($kitchenarr);exit;
$urls=$confirmamount->getUrlsArr(json_encode($temparr));
$confirmamount->sendFreeMessage($urls);
?>