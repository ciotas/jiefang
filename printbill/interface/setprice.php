<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class SetPrice{
	public function SetNewPrice($billid,$foodid,$foodarr,$cooktype,$foodnum,$newprice){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->SetNewPrice($billid, $foodid, $foodarr, $cooktype, $foodnum, $newprice);
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
$setprice=new SetPrice();
if(isset($_POST['billid'])){
	$uid=$_POST['uid'];
	$billid=$_POST['billid'];
	$foodid=$_POST['foodid'];
	$foodnum =$_POST['foodnum'];
	$newprice=$_POST['newprice'];
	$cooktype=$_POST['cooktype'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$billarr=$setprice->getOneFoodInBill($billid,$foodid);
	$setprice->SetNewPrice($billid, $foodid, $billarr['food'], $cooktype, $foodnum, $newprice);
	header('Content-type: application/json');
	echo json_encode(array("status"=>"ok"));
	exit;
	$sessionresult=$setprice->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$billid.$foodid.$newprice.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$setprice->updateCusSession($uid,$session);break;
			}
			$billarr=$setprice->getOneFoodInBill($billid,$foodid);
			$setprice->SetNewPrice($billid, $foodid, $billarr['food'], $cooktype, $foodnum, $newprice);
			header('Content-type: application/json');
			echo json_encode(array("status"=>"ok"));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
// exit;
$uid="554ad8cc5bc109d7518b45b5";
$billid="566020c85bc109ab5c8b5408";
$foodid="554b05d25bc109d4518b4620";
$foodnum ="1";
$newprice="40";
$cooktype="";
$billarr=$setprice->getOneFoodInBill($billid,$foodid);
// print_r($billarr);exit;
$setprice->SetNewPrice($billid, $foodid, $billarr['food'], $cooktype, $foodnum, $newprice);
?>