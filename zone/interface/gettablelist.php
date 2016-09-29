<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetTableList{
	public function getUseTables($shopid,$uid) {
		return Zone_InterfaceFactory::createInstanceTabDAL()->getUseTables($shopid,$uid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$gettablelist=new GetTableList();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$gettablelist->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$gettablelist->updateCusSession($uid, $session);
			}
			$result=$gettablelist->getUseTables($shopid,$uid);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"tabs"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$gettablelist->getUseTables("547430f016c10932708b4624","54769d6816c10909058b4651");
print_r($result);
echo json_encode(array("token"=>"","tabs"=>$result));
?>