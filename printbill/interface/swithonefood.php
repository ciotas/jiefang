<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class SwitchOneFood{
	public function switchOneFoodBehavior($foodid,$foodamount,$cooktype,$oldbillid,$tabid){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->switchOneFoodBehavior($foodid, $foodamount, $cooktype, $oldbillid, $tabid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function getOneFoodInBill($billid, $foodid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneFoodInBill($billid, $foodid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
	public function addSwitchFoodRecordData($oldbillid,$foodid,$foodamount,$tabid,$timestamp){
		PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->addSwitchFoodRecordData($oldbillid, $foodid, $foodamount, $tabid, $timestamp);
	}
	public function printSwitchSheet($inputarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->printSwitchSheet($inputarr);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function getTablenameByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTablenameByTabid($tabid);
	}
}
$switchonefood=new SwitchOneFood();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$oldbillid=$_POST['oldbillid'];
	$foodid=$_POST['foodid'];
	$foodamount=$_POST['foodamount'];
	$cooktype=$_POST['cooktype'];
	$tabid=$_POST['tabid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$switchonefood->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$oldbillid.$foodid.$tabid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$switchonefood->updateCusSession($uid, $session);
			}
			$billarr=$switchonefood->getOneFoodInBill($oldbillid,$foodid);
			$switchonefood->switchOneFoodBehavior($foodid, $foodamount, $cooktype, $oldbillid, $tabid);
			//file_put_contents("/var/www/html/printbill/lasttime", json_encode($billarr));
			$oldtabname=$switchonefood->getTablenameByTabid($billarr['tabid']);
			$newtabname=$switchonefood->getTablenameByTabid($tabid);
			$inputarr=array(
				"uid"=>$uid,
				"shopid"=>$billarr['shopid'],
				"oldtabname"=>$oldtabname,
				"newtabname"=>$newtabname,
				"foodid"=>$foodid,
				"foodname"=>$billarr['foodname'],
				"timestamp"=>time(),
			);
			
			$printarr=$switchonefood->printSwitchSheet($inputarr);
			$switchonefood->sendFreeMessage($printarr);//打印
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
			//添加转菜记录
			$switchonefood->addSwitchFoodRecordData($oldbillid, $foodid, $foodamount, $tabid, $timestamp);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$oldbillid="566272445bc1094d3e8b5429";
$foodid="554b05d25bc109d4518b4620";
$tabid="5565d1015bc1092b7a8b9687";
$billarr=$switchonefood->getOneFoodInBill($oldbillid,$foodid);
$oldtabname=$switchonefood->getTablenameByTabid($billarr['tabid']);
$newtabname=$switchonefood->getTablenameByTabid($tabid);
$inputarr=array(
		"uid"=>$uid,
		"shopid"=>$billarr['shopid'],
		"oldtabname"=>$oldtabname,
		"newtabname"=>$newtabname,
		"foodid"=>$foodid,
		"foodname"=>$billarr['foodname'],
		"timestamp"=>time(),
);
// print_r($inputarr);exit;
$printarr=$switchonefood->printSwitchSheet($inputarr);
// print_r($printarr);exit;
$switchonefood->sendFreeMessage($printarr);//打印
?>
