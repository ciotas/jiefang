<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class UrgeFood{
	public function getOneBillInfoByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillInfoByBillid($billid);
	}
	public function sendFreeMessage($msg) {
		return PRINT_InterfaceFactory::createInstancePlaceOrderDAL()->sendFreeMessage($msg);
	}
	public function getUrgeContentData($inputarr){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getUrgeContentData($inputarr);
	}
	public function getTablenameByTabid($tabid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getTablenameByTabid($tabid);
	}
	public function getFoodInfoByFoodid($foodid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getFoodInfoByFoodid($foodid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
	
}
$urgefood=new UrgeFood();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$foodid=$_POST['foodid'];
	$shopid=$_POST['shopid'];
	$billid=$_POST['billid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$urgefood->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$billid.$foodid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$urgefood->updateCusSession($uid,$session);break;
			}
			$billdarr=$urgefood->getOneBillInfoByBillid($billid);
			$tabname=$urgefood->getTablenameByTabid($billdarr['tabid']);
			$foodarr=$urgefood->getFoodInfoByFoodid($foodid);
			$inputarr=array(
					"shopid"=>$shopid,
					"tabname"=>$tabname,
					"foodid"=>$foodid,
					"foodname"=>$foodarr['foodname'],
					"donetime"=>$billdarr['timestamp'],
			);
			$printarr=$urgefood->getUrgeContentData($inputarr);
			$urgefood->sendFreeMessage($printarr);//打印
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$billid="5572af927cc1091c058b45fb";
$foodid="554b0c6b5bc109d8518b45e5";
$shopid="554ad9615bc109d8518b45d2";
$billdarr=$urgefood->getOneBillInfoByBillid($billid);
$tabname=$urgefood->getTablenameByTabid($billdarr['tabid']);
$foodarr=$urgefood->getFoodInfoByFoodid($foodid);
$inputarr=array(
		"shopid"=>$shopid,
		"tabname"=>$tabname,
		"foodid"=>$foodid,
		"foodname"=>$foodarr['foodname'],
		"donetime"=>$billdarr['timestamp'],
);
// print_r($inputarr);exit;
$printarr=$urgefood->getUrgeContentData($inputarr);
print_r($printarr);
$urgefood->sendFreeMessage($printarr);//打印
?>