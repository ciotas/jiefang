<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class UpdateZone{
	public function updateZoneData($zoneid, $op, $newval){
		return Zone_InterfaceFactory::createInstanceZoneDAL()->updateZoneData($zoneid, $op, $newval);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$updatezone=new UpdateZone();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$zoneid=$_POST['zoneid'];
	$op=$_POST['op'];
	$newval=$_POST['newval'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$updatezone->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$zoneid.$op.$newval.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$updatezone->updateShopSession($shopid,$session);break;
			}
			$updatezone->updateZoneData($zoneid, $op, $newval);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$updatezone->updateZoneData("5474535516c10932708b4626", "zonename", "大厅");
?>