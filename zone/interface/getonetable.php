<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetOneTable{
	public function GetOneTableData($tabid,$session){
		return Zone_InterfaceFactory::createInstanceTabDAL()->getOneTable($tabid,$session);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$getonetable=new GetOneTable();
if(isset($_POST['tabid'])){
	$tabid=$_POST['tabid'];
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getonetable->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$tabid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getonetable->updateShopSession($shopid,$session);break;
			}
			$result=$getonetable->GetOneTableData($tabid,$session);
			header('Content-type: application/json');
			echo json_encode($result);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$getonetable->GetOneTableData("553b11c416c1092f2f8b4754","");
print_r($result);
echo json_encode($result);
?>