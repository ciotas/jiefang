<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class FoodFlag{
	public function updateFoodStatus($billid,$foodarr,$foodid,$foodstatus,$foodamount,$cooktype){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateFoodStatus($billid, $foodarr, $foodid, $foodstatus,$foodamount, $cooktype);
	}
	public function getOneFoodInBill($billid, $foodid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneFoodInBill($billid, $foodid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$foodflag=new FoodFlag();
if(isset($_POST['foodid'])){
	$foodid=$_POST['foodid'];
	$uid=$_POST['uid'];
	$billid=$_POST['billid'];
	$foodamount =$_POST['foodamount'];
	$cooktype=$_POST['cooktype'];
	$foodstatus=$_POST['foodstatus'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$foodflag->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($billid.$uid.$foodid.$foodamount.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$foodflag->updateCusSession($uid,$session);break;
			}
			$billarr=$foodflag->getOneFoodInBill($billid,$foodid);
			$result=$foodflag->updateFoodStatus($billid, $billarr['food'], $foodid,$foodstatus, $foodamount, $cooktype);
			header('Content-type: application/json');
			echo json_encode($result);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$billid="565424665bc10978138b48ad";
$foodid="55689c897cc10918058b456a";
$billarr=$foodflag->getOneFoodInBill($billid,$foodid);
$foodamount="1";
// print_r($billarr);exit;
$result=$foodflag->updateFoodStatus($billid, $billarr['food'], $foodid, $foodamount, $cooktype);
var_dump($result);
?>