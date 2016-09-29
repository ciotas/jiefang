<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/des.php');
class JudgeVip{
	public function isRegByphone($phone,$shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorOneDAL()->isRegByphone($phone,$shopid);
	}
}
$judgevip=new JudgeVip();
if(isset($_GET['phone'])){
	$userphone=$_GET['phone'];
	$shopid=$_GET['shopid'];
	$phonecrypt = new CookieCrypt($cusphonekey);
	$userphone=$phonecrypt->encrypt($userphone);
	$isreg=$judgevip->isRegByphone($userphone,$shopid);
	echo $isreg;
}

?>