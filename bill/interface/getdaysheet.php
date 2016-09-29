<?php 
require_once ('/var/www/html/bill/global.php');
require_once (Bill_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetDaySheet{
	public function getDaySheetData($shopid,$theday,$token){
		return Bill_InterfaceFactory::createInstanceBillDAL()->getDaySheetData($shopid, $theday,$token);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$getdaysheet=new GetDaySheet();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$theday=$_POST['theday'];//格式2015-05-01
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getdaysheet->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$theday.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getdaysheet->updateShopSession($shopid,$session);break;
			}
			$result=$getdaysheet->getDaySheetData($shopid, $theday,$session);
			header('Content-type: application/json');
			echo json_encode($result);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$getdaysheet->getDaySheetData("554ad9615bc109d8518b45d2", "2015-5-7","");
print_r($result);
echo json_encode($result);
?>