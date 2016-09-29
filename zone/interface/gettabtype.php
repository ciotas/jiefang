<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetTabType{
	public function getTabTypeData($shopid){
		return Zone_InterfaceFactory::createInstanceTabDAL()->getTabTypeData($shopid);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$gettabtype=new GetTabType();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$gettabtype->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$gettabtype->updateShopSession($shopid,$session);break;
			}
			$result=$gettabtype->getTabTypeData($shopid);
			header('Content-type: application/json');
			echo json_encode(array("data"=>$result,"token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$gettabtype->getTabTypeData("554ad9615bc109d8518b45d2");
print_r($result);
echo json_encode(array("data"=>$result,"token"=>""));
?>