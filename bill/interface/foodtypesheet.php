<?php 
require_once ('/var/www/html/bill/global.php');
require_once (Bill_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class FoodTypeSheet{
	public function getFoodtypeSheetData($shopid, $theday,$starttime,$endtime){
		return Bill_InterfaceFactory::createInstanceBillDAL()->getFoodtypeSheetData($shopid, $theday,$starttime,$endtime);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$foodtypesheet=new FoodTypeSheet();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$theday=$_POST['theday'];//格式2015-05-01
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$foodtypesheet->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$theday.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$foodtypesheet->updateShopSession($shopid,$session);break;
			}
			$result=$foodtypesheet->getFoodtypeSheetData($shopid, $theday,"","");
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"data"=>$result));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$foodtypesheet->getFoodtypeSheetData("554ad9615bc109d8518b45d2", "2015-5-10","","");
print_r($result);exit;
echo json_encode(array("token"=>"","data"=>$result));
?>
