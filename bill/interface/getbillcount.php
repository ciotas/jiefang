<?php 
require_once ('/var/www/html/bill/global.php');
require_once (DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetBillCount{
	public function getData($uid){
		return InterfaceFactory::createInstanceBillCountDAL()->getBillCountData($uid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$getbillcount=new GetBillCount();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getbillcount->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getbillcount->updateCusSession($uid, $session);
			}
			$result=$getbillcount->getData($uid);
			echo json_encode(array("token"=>$token,"data"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$getbillcount->getData("5480057716c1099d198b45a1");
print_r($result);exit;
echo json_encode($result);
?>