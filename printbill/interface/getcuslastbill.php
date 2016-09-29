<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetCusLatBill{
	public function getLastBillData($uid){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->getLastBillData($uid);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$getcuslastbill=new GetCusLatBill();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$uid=$_POST['uid'];
	
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getcuslastbill->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$uid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getcuslastbill->updateShopSession($shopid,$session);break;
			}
			$result=$getcuslastbill->getLastBillData($uid);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"data"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$getcuslastbill->getLastBillData("541fd6be16c10909058b45a5");
print_r($result);
?>