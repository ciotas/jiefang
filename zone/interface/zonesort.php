<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class ZoneSort{
	public function changeZoneSort($zoneno){
		Zone_InterfaceFactory::createInstanceZoneDAL()->changeZoneSort($zoneno);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$zonesort=new ZoneSort();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$zoneno=$_POST['zoneno'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$zonesort->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$zonesort->updateShopSession($shopid,$session);break;
			}
			header('Content-type: application/json');
			$zonesort->changeZoneSort($zoneno);
			echo json_encode(array("token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$shopid="547430f016c10932708b4624";
$zoneno=array(
		"553f73725bc10916618b4bd6"=>"0",	
		"551252ee5bc109da518b4567"=>"1",
		"552638815bc10913618b456b"=>"2",
);
echo json_encode($zoneno);exit;
$zonesort->changeZoneSort($zoneno);
?>