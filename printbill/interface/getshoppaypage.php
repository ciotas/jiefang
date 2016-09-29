<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetPayPage{
	public function getPayPageData($billid, $shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getPayPageData($billid, $shopid);
	}
	public function getTokenStatus($shopid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeSession($shopid);
	}
	public function updateShopSession($shopid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateShopSession($shopid, $session);
	}
}
$getpaypage=new GetPayPage();
if(isset($_POST['shopid'])){
	$billid=$_POST['billid'];
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getpaypage->getTokenStatus($shopid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($shopid.$billid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getpaypage->updateShopSession($shopid,$session);break;
			}
			$result=$getpaypage->getPayPageData($billid, $shopid);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"totalmoney"=>$result['totalmoney'],"fooddisaccountmoney"=>$result['fooddisaccountmoney'],"ctype"=>$result['ctype']));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$getpaypage->getPayPageData("555184425bc109d4518b47e5", "5539b36816c109ec748b4640");
print_r($result);
echo json_encode(array("token"=>"","totalmoney"=>$result['totalmoney'],"fooddisaccountmoney"=>$result['fooddisaccountmoney'],"ctype"=>$result['ctype']));
?>
