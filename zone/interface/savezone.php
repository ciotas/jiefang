<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class SaveZone{
	public function saveData($inputarr){
		Zone_InterfaceFactory::createInstanceZoneDAL()->saveZone($inputarr);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$savezone=new SaveZone();
if(isset($_POST['shopid'])){//$_POST
	$shopid=$_POST['shopid'];
	$zonename=$_POST['zonename'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$savezone->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$zonename.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$savezone->updateShopSession($shopid,$session);break;
			}
			$inputarr=array(
					"shopid"=>$shopid,
					"zonename"	=>$zonename,
			);
			$savezone->saveData($inputarr);
			header('Content-type: application/json');
			echo  json_encode(array("token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$zonename="C区";
$shopid="547430f016c10932708b4624";
$printerid="547454b916c1099d198b4590";
$savezone->saveData( $zonename,$printerid, $shopid);
?>