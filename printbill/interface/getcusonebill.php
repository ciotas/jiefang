<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetCusLatBill{
	public function getOneBillByBillid($billid,$token){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneBillByBillid($billid,$token);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$getcuslastbill=new GetCusLatBill();
if(isset($_POST['uid'])){
	$billid=$_POST['billid'];
	$uid=$_POST['uid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getcuslastbill->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$billid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getcuslastbill->updateCusSession($uid,$session);break;
			}
			$result=$getcuslastbill->getOneBillByBillid($billid,$session);
			header('Content-type: application/json');
			echo json_encode($result);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$getcuslastbill->getOneBillByBillid("552b8aa25bc109b0428b4568","");
print_r($result);
echo json_encode($result);
?>