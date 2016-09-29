<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class GetVipMoney{
	public function getAccountbance($shopid, $uid, $cardid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getAccountbance($shopid, $uid, $cardid);
	}
	public function getUidByphone($phone){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->getUidByphone($phone);
	}
}
$getvipmoney=new GetVipMoney();
if(isset($_GET['phone'])){
	$userphone=$_GET['phone'];
	$cardid=$_GET['cardid'];
	$shopid=$_SESSION['shopid'];
	$phonecrypt = new CookieCrypt($cusphonekey);
	$userphone=$phonecrypt->encrypt($userphone);
	$uid=$getvipmoney->getUidByphone($userphone);
	echo $getvipmoney->getAccountbance($shopid, $uid, $cardid);
}
?>