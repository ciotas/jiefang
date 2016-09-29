<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class IsSendVip{
	public function getUidByphone($phone){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getUidByphone($phone);
	}
	public function isSendToTheUser($shopid, $uid, $cardid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->isSendToTheUser($shopid, $uid, $cardid);
	}
}
$issendvip=new IsSendVip();
if(isset($_GET['phone'])){
	$userphone=$_GET['phone'];
	$cardid=$_GET['cardid'];
	$shopid=$_SESSION['shopid'];
	$phonecrypt = new CookieCrypt($cusphonekey);
	$userphone=$phonecrypt->encrypt($userphone);
	$uid=$issendvip->getUidByphone($userphone);
	$issended=$issendvip->isSendToTheUser($shopid, $uid, $cardid);
	echo $issended;
}
?>