<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetShopTable{
	public function getServerTabs($shopid,$uid){
		return Zone_InterfaceFactory::createInstanceTabDAL()->getServerTabs($shopid,$uid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$getshoptable=new GetShopTable();

if(isset($_REQUEST['uid'])){
	$uid=$_REQUEST['uid'];
	$shopid=$_REQUEST['shopid'];
	$timestamp=$_REQUEST['timestamp'];
	$signature=$_REQUEST['signature'];
	$sessionresult=$getshoptable->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getshoptable->updateCusSession($uid,$session);break;
			}
			$result=$getshoptable->getServerTabs($shopid, $uid);
			header('Content-type: application/json');
			echo json_encode($result);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$getshoptable->getServerTabs("5539b36816c109ec748b4640", "55e2f8b95bc109d03e8b5303");
// print_r($result);exit;
echo json_encode($result);
?>