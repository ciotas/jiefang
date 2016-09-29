<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetOneZone{
	public function getOneZoneData($zoneid,$token){
		return Zone_InterfaceFactory::createInstanceZoneDAL()->getOneZoneData($zoneid,$token);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$getonezone=new GetOneZone();
if(isset($_REQUEST['shopid'])){
	$shopid=$_REQUEST['shopid'];
	$zoneid=$_POST['zoneid'];
	$timestamp=$_REQUEST['timestamp'];
	$signature=$_REQUEST['signature'];
	$sessionresult=$getonezone->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$zoneid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getonezone->updateShopSession($shopid,$session);break;
			}
			$result=$getonezone->getOneZoneData($zoneid,$session);
			header('Content-type: application/json');
			echo json_encode($result);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$getonezone->getOneZoneData("5474535516c10932708b4626","");
print_r($result);
echo json_encode($result);
?>