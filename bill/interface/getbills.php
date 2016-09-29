<?php 
require_once ('/var/www/html/bill/global.php');
require_once (Bill_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetBills{
	public function getBillsData($shopid, $searchday){
		return Bill_InterfaceFactory::createInstanceBillDAL()->getBillsData($shopid, $searchday);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$getbills=new GetBills();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$searchday=$_POST['searchday'];
	$uid=$_POST['uid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getbills->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$searchday.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getbills->updateCusSession($uid, $session);
			}
			$result=$getbills->getBillsData($shopid, $searchday);
			header('Content-type: application/json');
			echo json_encode(array("code"=>"0","msg"=>"正确", "data"=>array("bills"=>$result)));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("code"=>"1","msg"=>"签名错误", "data"=>array() ));
		}
	}
}
exit;
$shopid="55d6ce105bc109f23e8b51db";
$searchday="2016-1-1";
$result=$getbills->getBillsData($shopid, $searchday);
print_r($result);
// echo json_encode($result);
?>