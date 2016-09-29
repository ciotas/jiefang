<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetTabBillid{
	public function getBillidByTabid($shopid,$tabid,$token){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillidByTabid($shopid, $tabid,$token);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$gettabbillid=new GetTabBillid();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$tabid=$_POST['tabid'];
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$gettabbillid->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$tabid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$gettabbillid->updateCusSession($uid,$session);break;
			}
			$result=$gettabbillid->getBillidByTabid($shopid, $tabid,$session);
			header('Content-type: application/json');
			echo json_encode($result);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$billid=$gettabbillid->getBillidByTabid("547430f016c10932708b4624", "552501e65bc10928568b456a");
echo $billid;
?>