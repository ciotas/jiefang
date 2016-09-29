<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SaveRule{
	public function saveRuleData($inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorThreeDAL()->saveRuleData($inputarr);
	}
	public function updateDonateticketData($ruleid, $inputarr){
		QuDian_InterfaceFactory::createInstanceMonitorThreeDAL()->updateDonateticketData($ruleid, $inputarr);
	}
}
$saverule=new SaveRule();
if(isset($_POST['fullmoney'])){
	$fullmoney=$_POST['fullmoney'];
	$sendmoney=$_POST['sendmoney'];
	$shopid=$_SESSION['shopid'];
	$ruleid=$_POST['ruleid'];
	$inputarr=array(
			"shopid"=>$shopid,
			"fullmoney"=>$fullmoney,
			"sendmoney"=>$sendmoney,
	);
	if(!empty($ruleid)){
		$saverule->updateDonateticketData($ruleid, $inputarr);
	}else{
		$saverule->saveRuleData($inputarr);
	}
	header("location: ".$base_url."activity/donateticket/rule.php");
}
?>