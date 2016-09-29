<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class GetPayPage{
	public function getPayPageData($billid, $shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getPayPageData($billid, $shopid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$getpaypage=new GetPayPage();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$billid=$_POST['billid'];
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$getpaypage->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$billid.$shopid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$getpaypage->updateCusSession($uid,$session);break;
			}
			$result=$getpaypage->getPayPageData($billid, $shopid);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session,"totalmoney"=>$result['totalmoney'],"deposit"=>$result['deposit'], "depositmoney"=>$result['depositmoney'], "fooddisaccountmoney"=>$result['fooddisaccountmoney'],"ctype"=>$result['ctype']));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$result=$getpaypage->getPayPageData("555c97a45bc109d27f8b4ad1", "554ad9615bc109d8518b45d2");
print_r($result);exit;
echo json_encode(array("token"=>"","totalmoney"=>$result['totalmoney'],"fooddisaccountmoney"=>$result['fooddisaccountmoney'],"ctype"=>$result['ctype']));
?>
