<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetLastBill{
	public function getLastBillData($uid){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->getLastBillData($uid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$getlastbill=new GetLastBill();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getlastbill->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getlastbill->updateCusSession($uid, $session);
			}
			$result=$getlastbill->getLastBillData($uid);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"data"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result= $getlastbill->getLastBillData("54769d6816c10909058b4651");
print_r($result);exit;
echo json_encode(array("token"=>"","data"=>$result));
?>