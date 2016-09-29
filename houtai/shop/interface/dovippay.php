<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class DoVipPay{
	public function getUidByphone($phone){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getUidByphone($phone);
	}
	public function consumeByVipcard($inputarr){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->consumeByVipcard($inputarr);
	}
	public function getAccountbance($shopid, $uid, $cardid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getAccountbance($shopid, $uid, $cardid);
	}
}
$dovippay=new DoVipPay();
if(isset($_POST['shopid'])){
	$shopid=$_POST['shopid'];
	$userphone=$_POST['userphone'];
	if(empty($userphone)){
		header("location: ../vippay.php?status=phone_empty");exit;
	}
	$cardid=$_POST['cardid'];
	if(empty($cardid)){
		header("location: ../vippay.php?status=cardid_empty");exit;
	}
	$vippaymoney=$_POST['vippaymoney'];
	if(empty($vippaymoney)){
		header("location: ../vippay.php?status=vippaymoney_empty");exit;
	}
	$phonecrypt = new CookieCrypt($cusphonekey);
	$userphone=$phonecrypt->encrypt($userphone);
	$uid=$dovippay->getUidByphone($userphone);
	if(empty($uid)){
		header("location: ../vippay.php?status=phone_unreg");exit;
	}
	$accountbalance=$dovippay->getAccountbance($shopid, $uid, $cardid);
	if($vippaymoney>$accountbalance){
		header("location: ../vippay.php?status=full");exit;
	}
	$inputarr=array(
			"shopid"=>$shopid,
			"uid"=>$uid,
			"cardid"=>$cardid,
			"vippaymoney"=>$vippaymoney,	
	);
	$viprcid=$dovippay->consumeByVipcard($inputarr);
	header("location: ../yourvippay.php?viprcid=".$viprcid);exit;
}
?>