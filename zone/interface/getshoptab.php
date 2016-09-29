<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetShopTab{
	public function getShopTablesData($shopid){
		return Zone_InterfaceFactory::createInstanceTabDAL()->getShopTablesData($shopid);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$getshoptab=new GetShopTab();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$timestamp=$_REQUEST['timestamp'];
	$signature=$_REQUEST['signature'];
	$sessionresult=$getshoptab->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getshoptab->updateShopSession($shopid,$session);break;
			}
			$result=$getshoptab->getShopTablesData($shopid);
			header('Content-type: application/json');
			echo json_encode($result);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$getshoptab->getShopTablesData("5539b36816c109ec748b4640");
// print_r($result);
echo json_encode($result);
?>