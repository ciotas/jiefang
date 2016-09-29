<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetServerTabs{
	public function getServerTabsData($shopid,$uid){
		return Zone_InterfaceFactory::createInstanceTabDAL()->getServerTabsData($shopid, $uid);
	}
	
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$getservertabs=new GetServerTabs();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getservertabs->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getservertabs->updateCusSession($uid, $session);
			}
			$result=$getservertabs->getServerTabsData($shopid, $uid);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"data"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}

exit;
$result=$getservertabs->getServerTabsData("5539b36816c109ec748b4640", "55e2f8b95bc109d03e8b5303");
// print_r($result);
echo json_encode(array("token"=>"","data"=>$result));
?>