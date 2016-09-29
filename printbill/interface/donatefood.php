<?php 
require_once ('/var/www/html/printbill/global.php');
require_once (PRINT_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/token/Factory/InterfaceFactory.php');
class DonateFood{
	public function doDonateFood($billid, $foodid, $donatenum,$foodnum,$cooktype){
		return PRINT_InterfaceFactory::createInstanceDoWorkerDAL()->doDonateFood($billid, $foodid, $donatenum,$foodnum,$cooktype);
	}
	public function getCusTokenStatus($uid){
		return Token_InterfaceFactory::createInstanceTokenDAL()->JudgeCusSession($uid);
	}
	public function updateCusSession($uid,$session){
		return Token_InterfaceFactory::createInstanceTokenDAL()->updateCusSession($uid, $session);
	}
}
$donatefood=new DonateFood();
if(isset($_POST['uid'])){
	$uid=$_POST['uid'];
	$billid=$_POST['billid'];
	$foodid=$_POST['foodid'];
	$donatenum=$_POST['donatenum'];
	$foodnum =$_POST['foodnum'];
	$cooktype=$_POST['cooktype'];
	$timestamp=$_POST['timestamp'];
	$signature=$_POST['signature'];
	$sessionresult=$donatefood->getCusTokenStatus($uid);
	if(is_array($sessionresult)&&!empty($sessionresult)){
		$token=$sessionresult['token'];
		$serversign=strtoupper(md5($uid.$billid.$foodid.$donatenum.$timestamp.$token));
		if($serversign==$signature){//验证通过
			switch ($sessionresult['status']){
				case "valid":$session="";break;
				case "invalid":$session=session_id();$donatefood->updateCusSession($uid,$session);break;
			}
			$donatefood->doDonateFood($billid, $foodid, $donatenum,$foodnum,$cooktype);
			header('Content-type: application/json');
			echo json_encode(array("token"=>$session));
		}else{
			header('Content-type: application/json');
			echo json_encode(array("errcode"=>"10000","errmsg"=>"invalid credential"));
		}
	}
}
exit;
$billid="552b3b0f5bc109cf318b4567";
$foodid="54748bbe16c1090b058b462e";
$donatenum="2";
$donatefood->doDonateFood($billid, $foodid, $donatenum);
?>