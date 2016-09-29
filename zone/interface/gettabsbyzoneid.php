<?php 
require_once ('/var/www/html/zone/global.php');
require_once (Zone_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetTabsByZoneid{
	public function getTabDataByzoneid($zoneid){
		return Zone_InterfaceFactory::createInstanceTabDAL()->getTabDataByzoneid($zoneid);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$gettabsbyzoneid=new GetTabsByZoneid();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$zoneid=$_POST['zoneid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$gettabsbyzoneid->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$zoneid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$gettabsbyzoneid->updateShopSession($shopid,$session);break;
			}
			$result=$gettabsbyzoneid->getTabDataByzoneid($zoneid);
			header('Content-type: application/json');
			echo json_encode(array("data"=>$result,"token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$gettabsbyzoneid->getTabDataByzoneid("554adaca5bc109d5518b45d7");
print_r($result);
echo json_encode(array("data"=>$result,"token"=>""));
?>