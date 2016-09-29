<?php 
require_once ('/var/www/html/bill/global.php');
require_once (DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetBillDetails{
	public function getBillData($billid){
		return InterfaceFactory::createInstanceBillDataDAL()->getBillData($billid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$getbilldetails=new GetBillDetails();
if(isset($_POST['billid'])){
	$billid=$_POST['billid'];
	$uid=$_POST['uid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getbilldetails->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$billid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getbilldetails->updateCusSession($uid, $session);
			}
			$result=$getbilldetails->getBillData($billid);
			echo json_encode(array("token"=>$session,"data"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$getbilldetails->getBillData("543b5fa116c1099d198b456b");
echo json_encode(array("token"=>"","data"=>$result));
print_r($result);
?>