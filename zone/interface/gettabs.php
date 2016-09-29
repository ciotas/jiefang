<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetTabs{
	public function getTabsData($shopid){
		return Zone_InterfaceFactory::createInstanceTabDAL()->getTabsData($shopid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$gettabs=new GetTabs();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$gettabs->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$gettabs->updateCusSession($uid, $session);
			}
			$result=$gettabs->getTabsData($shopid);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"data"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$gettabs->getTabsData("547430f016c10932708b4624");
print_r($result);
echo json_encode(array("token"=>$session,"data"=>$result));
?>