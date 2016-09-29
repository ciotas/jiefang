<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class UpdateTable{
	public function updateData($tabid,$op,$newval){
		Zone_InterfaceFactory::createInstanceTabDAL()->updateData($tabid, $op, $newval);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$updatetable=new UpdateTable();
if(isset($_POST['tabid'])){//$_POST
	$tabid=$_POST['tabid'];
	$shopid=$_POST['shopid'];
	$op=$_POST['op'];
	$newval=$_POST['newval'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$updatetable->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$tabid.$op.$newval.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$updatetable->updateShopSession($shopid,$session);break;
			}
			$updatetable->updateData($tabid, $op, $newval);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
// $updatetable->updateData("53f80b1f16c109dd638b4567", "tabname", "A01");
?>