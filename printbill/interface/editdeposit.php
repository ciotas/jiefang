<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class EditDeposit{
	public function getBillPaystatusByBillid($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getBillPaystatusByBillid($billid);
	}
	public function getOneDesposit($billid){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->getOneDesposit($billid);
	}
	public function updateBillDeposit($billid,$newdeposit){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->updateBillDeposit($billid, $newdeposit);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$editdeposit=new EditDeposit();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$billid=$_POST['billid'];
	$shopid=$_POST['shopid'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$editdeposit->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$shopid.$billid.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$editdeposit->updateCusSession($uid,$session);break;
			}
// 			$paystatus=$editdeposit->getBillPaystatusByBillid($billid);
			$deposit=$editdeposit->getOneDesposit($billid);
			if($deposit=="1"){
				$newdeposit="0";
			}else{
				$newdeposit="1";
			}
			$editdeposit->updateBillDeposit($billid, $newdeposit);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
?>