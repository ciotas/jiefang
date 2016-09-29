<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetOneCusBill{
	public function getData($billid){
		return PRINT_InterfaceFactory::createInstanceHandleDAL()->getBillData($billid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$getcusbill=new GetOneCusBill();
if(isset($_POST['billid'])){
	$uid=$_POST['uid'];
	$billid=$_POST['billid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getcusbill->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$billid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getcusbill->updateCusSession($uid, $session);
			}
			$result=$getcusbill->getData($billid);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"data"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result= $getcusbill->getData("5448642416c1090c058b45f3");
print_r($result);exit;
?>