<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class Payage{
	public function getPayPageData($billid, $shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getPayPageData($billid, $shopid);
	}
	public function getPayRight($uid,$shopid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getPayRight($uid, $shopid);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$payage=new Payage();
if(isset($_POST['billid'])){
	$billid=$_POST['billid'];
	$shopid=$_POST['shopid'];
	$uid=$_POST['uid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$payage->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$billid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$payage->updateCusSession($uid,$session);break;
			}
			$roler=$payage->getPayRight($uid, $shopid);
			$arr=$payage->getPayPageData($billid, $shopid);
			header('Content-type: application/json');
			echo json_encode(array("roler"=>$roler,"totalmoney"=>$arr['totalmoney'],"fooddisaccountmoney"=>$arr['fooddisaccountmoney'],"topclearmoney"=>$arr['topclearmoney'],"deposit"=>$arr['deposit'],"depositmoney"=>$arr['depositmoney'],"ctype"=>$arr['ctype']));
			
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$billid="5654594d5bc109aa5c8b5191";
$shopid="554ad9615bc109d8518b45d2";
$uid="554ad8cc5bc109d7518b45b5";
$roler=$payage->getPayRight($uid, $shopid);
// var_dump($roler);exit;
$arr=$payage->getPayPageData($billid, $shopid);
// print_r($couponarr);exit;
echo json_encode(array("roler"=>$roler,"totalmoney"=>$arr['totalmoney'],"fooddisaccountmoney"=>$arr['fooddisaccountmoney'],"topclearmoney"=>$arr['topclearmoney'],"deposit"=>$arr['deposit'],"depositmoney"=>$arr['depositmoney'],"ctype"=>$arr['ctype']));
?>