<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetZone{
	public function getData($shopid){
		return Zone_InterfaceFactory::createInstanceZoneDAL()->getZoneByShopid($shopid);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$getzone=new GetZone();
if(isset($_REQUEST['shopid'])){
	$shopid=$_REQUEST['shopid'];
	$timestamp=$_REQUEST['timestamp'];
	$signature=$_REQUEST['signature'];
	$sessionresult=$getzone->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getzone->updateShopSession($shopid,$session);break;
			}
			$result= $getzone->getData($shopid,$session);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"zone"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result= $getzone->getData("547430f016c10932708b4624","");
print_r($result);
echo json_encode(array("token"=>"","zone"=>$result));
?>