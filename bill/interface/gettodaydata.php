<?php 
require_once ('/var/www/html/bill/global.php');
require_once (Bill_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetTodayData{
	public function getTodayOnlineData($shopid,$theday,$token){
		return Bill_InterfaceFactory::createInstanceBillDAL()->getTodayOnlineData($shopid,$theday,$token);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
	public function getTheday($shopid){
		return Bill_InterfaceFactory::createInstanceBillDAL()->getTheday($shopid);
	}
}
$gettodaydata=new GetTodayData();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$gettodaydata->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$gettodaydata->updateShopSession($shopid,$session);break;
			}
			$theday=$gettodaydata->getTheday($shopid);
			$result=$gettodaydata->getTodayOnlineData($shopid,$theday,$session);
			header('Content-type: application/json');
			echo json_encode($result);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$theday=$gettodaydata->getTheday("5555b4a55bc109d6518b5f4c");
$result=$gettodaydata->getTodayOnlineData("5555b4a55bc109d6518b5f4c",$theday,"");
print_r($result);
echo json_encode($result);
?>