<?php 
require_once ('/var/www/html/bill/global.php');
require_once (Bill_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class FoodsSheet{
	public function getFoodSheetData($shopid,$theday, $starttime,$endtime){
		return Bill_InterfaceFactory::createInstanceBillDAL()->getFoodSheetData($shopid, $theday, $starttime,$endtime);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$foodssheet=new FoodsSheet();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$theday=$_POST['theday'];//格式2015-05-01
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$foodssheet->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$theday.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$foodssheet->updateShopSession($shopid,$session);break;
			}
			$result=$foodssheet->getFoodSheetData($shopid, $theday,"","");
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"data"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$foodssheet->getFoodSheetData("547430f016c10932708b4624", "2015-4-18");
print_r($result);
echo json_encode(array("token"=>"","data"=>$result));
?>