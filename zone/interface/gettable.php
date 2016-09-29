<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetTable{
	public function getData($shopid){
		return Zone_InterfaceFactory::createInstanceTabDAL()->findTab($shopid);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$gettab=new GetTable();
if(isset($_REQUEST['shopid'])){//改为post
	$shopid=$_REQUEST['shopid'];
	$timestamp=$_REQUEST['timestamp'];
	$signature=$_REQUEST['signature'];
	$sessionresult=$gettab->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$gettab->updateShopSession($shopid,$session);break;
			}
			$result= $gettab->getData($shopid);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"tabs"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result= $gettab->getData("547430f016c10932708b4624");
print_r($result);
echo json_encode(array("token"=>"","tabs"=>$result));
?>