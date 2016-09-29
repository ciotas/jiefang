<?php 
require_once ('/var/www/html/bill/global.php');
require_once (Bill_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetTakeoutSheet{
	public function getTakeoutData($shopid){
		return Bill_InterfaceFactory::createInstanceBillDAL()->getTakeoutData($shopid);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$gettakeoutsheet=new GetTakeoutSheet();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$gettakeoutsheet->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			$arr=$gettakeoutsheet->getTakeoutData($shopid);
			header('Content-type: application/json');
			echo json_encode($arr);
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
	
}
exit;
$shopid="554ad9615bc109d8518b45d2";
$arr=$gettakeoutsheet->getTakeoutData($shopid);
print_r($arr);
echo json_encode($arr);
?>